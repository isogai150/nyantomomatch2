<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CatPost;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class PostController extends Controller
{
    /**
     * 投稿一覧表示
     * 
     * トップページで全投稿を表示する。
     * タイトルや地域での検索、並び替えにも対応。
     */
    public function index(Request $request)
    {
        $query = Post::query()->whereNull('deleted_at');

        // 検索（タイトル or 地域）
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('region', 'like', '%' . $request->search . '%');
            });
        }

        // 並び替え（新着順・古い順・人気順）
        $sort = $request->get('sort', 'new');
        match ($sort) {
            'old' => $query->orderBy('created_at', 'asc'),
            'popular' => $query->withCount('favorites')->orderBy('favorites_count', 'desc'),
            default => $query->orderBy('created_at', 'desc')
        };

        // 画像付き投稿を取得（ページネーション）
        $catposts = $query->with('images')->paginate(12);

        return view('home.index', compact('catposts'));
    }

    /**
     * 投稿詳細表示
     * 
     * 投稿の詳細ページを表示。
     * 投稿者情報、画像、動画をリレーション込みで取得。
     */
    public function detail(Post $post)
    {
        $post->load(['user', 'images', 'videos']);
        return view('catpost.detail', compact('post'));
    }

    /**
     * 自分の投稿一覧表示
     * 
     * ログイン中ユーザーが投稿した猫一覧を表示。
     */
    public function myCatpost()
    {
        $user = Auth::user();

        $myCatposts = Post::with('images')
            ->withCount('favorites')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('authority.catpost.index', compact('myCatposts'));
    }

    /**
     * 投稿削除処理
     * 
     * 投稿者本人または管理者のみ削除可能。
     */
    public function destroy(Post $post)
    {
        $user = auth()->user();
        $admin = auth('admin')->user();

        // 権限チェック
        if (!$admin && $post->user_id !== $user->id) {
            abort(403, 'この投稿を削除する権限がありません。');
        }

        $post->delete();

        $redirect = $admin
            ? redirect()->route('admin.post.reports')->with('success', '投稿を削除しました')
            : redirect()->route('mycatpost.index')->with('success', '投稿を削除しました');

        return $redirect;
    }

    /**
     * 猫の情報投稿作成画面
     */
    public function create()
    {
        $user = Auth::user();
        return view('authority.catpost.create', ['user' => $user]);
    }

    /**
     * 投稿保存処理
     * 
     * バリデーション済みデータを保存し、画像・動画もS3へアップロード。
     */
    public function store(CatPost $request)
    {
        try {
            $validated = $request->validated();
            $disk = config('filesystems.default');

            // 投稿データ作成
            $post = new Post();
            $post->fill($validated);
            $post->user_id = Auth::id();
            $post->save();

            // ====== 画像保存 ======
            if ($request->hasFile('image')) {
                $images = is_array($request->file('image'))
                    ? $request->file('image')
                    : [$request->file('image')];

                foreach (array_slice($images, 0, 3) as $imageFile) {
                    if ($imageFile->isValid()) {
                        $fileName = uniqid() . '.' . $imageFile->getClientOriginalExtension();
                        $path = $imageFile->storeAs('post_images', $fileName, $disk);
                        $post->images()->create(['image_path' => $fileName]);

                        Log::info('Image uploaded', [
                            'user_id' => Auth::id(),
                            'path' => $path,
                            'disk' => $disk
                        ]);
                    }
                }
            }

            // ====== 動画保存 ======
            if ($request->hasFile('video')) {
                $videoFile = $request->file('video');
                if ($videoFile->isValid()) {
                    $videoName = uniqid() . '.' . $videoFile->getClientOriginalExtension();
                    $path = $videoFile->storeAs('post_videos', $videoName, $disk);
                    $post->videos()->create(['video_path' => $videoName]);

                    Log::info('Video uploaded', [
                        'user_id' => Auth::id(),
                        'path' => $path,
                        'disk' => $disk,
                        'size' => round($videoFile->getSize() / 1024 / 1024, 2) . 'MB',
                    ]);
                } else {
                    Log::warning('Invalid video file', [
                        'user_id' => Auth::id(),
                        'error_code' => $videoFile->getError(),
                    ]);
                }
            }

            Log::info('Post created successfully', [
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'disk' => $disk
            ]);

            return redirect()
                ->route('catpost.index')
                ->with('success', '投稿が作成されました！')
                ->with('clear_storage', true);

        } catch (Exception $e) {
            Log::error('Post creation failed', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return back()->withErrors(['error' => '投稿の作成中にエラーが発生しました。'])->withInput();
        }
    }

    /**
     * 編集画面表示
     * 
     * 投稿に紐づく画像・動画を読み込み、残りの追加可能枠を計算。
     */
    public function edit($id)
    {
        $post = Post::with(['images', 'videos'])->findOrFail($id);
        $maxImages = 3;
        $remainingImageSlots = max(0, $maxImages - $post->images->count());

        return view('authority.catpost.edit', compact('post', 'remainingImageSlots'));
    }

    /**
     * 投稿更新処理
     * 
     * 投稿者本人のみ編集可能。新規メディア追加にも対応。
     */
    public function update(CatPost $request, Post $post)
    {
        $user = Auth::user();

        if ($post->user_id !== $user->id) {
            abort(403, 'この投稿を編集する権限がありません。');
        }

        $validated = $request->validated();
        $disk = config('filesystems.default');

        // 投稿内容を更新
        $post->fill($validated);
        $post->save();

        // ====== 新しい画像を追加 ======
        if ($request->hasFile('images')) {
            $currentCount = $post->images->count();
            $newCount = count($request->file('images'));
            if ($currentCount + $newCount > 3) {
                return back()->withErrors(['images' => '画像は最大3枚までです。']);
            }

            foreach ($request->file('images') as $imageFile) {
                $fileName = uniqid() . '.' . $imageFile->getClientOriginalExtension();
                $path = $imageFile->storeAs('post_images', $fileName, $disk);
                $post->images()->create(['image_path' => $fileName]);

                Log::info('Image updated', [
                    'user_id' => Auth::id(),
                    'path' => $path,
                    'disk' => $disk
                ]);
            }
        }

        // ====== 動画更新 ======
        if ($request->hasFile('video')) {
            // 既存動画を削除してから新規追加
            foreach ($post->videos as $video) {
                Storage::disk($disk)->delete('post_videos/' . $video->video_path);
                $video->delete();
            }

            $videoFile = $request->file('video');
            $videoName = uniqid() . '.' . $videoFile->getClientOriginalExtension();
            $path = $videoFile->storeAs('post_videos', $videoName, $disk);
            $post->videos()->create(['video_path' => $videoName]);

            Log::info('Video updated', [
                'user_id' => Auth::id(),
                'path' => $path,
                'disk' => $disk
            ]);
        }

        Log::info('Post updated successfully', [
            'user_id' => Auth::id(),
            'post_id' => $post->id
        ]);

        return redirect()->route('mycatpost.index')->with('success', '投稿が更新されました！');
    }

    /**
     * 画像・動画削除処理
     * 
     * Ajaxで画像や動画を個別削除。
     */
    public function deleteMedia($type, $id)
    {
        $media = $type === 'image'
            ? \App\Models\PostImage::findOrFail($id)
            : \App\Models\PostVideo::findOrFail($id);

        $filePath = 'storage/' . ($media->image_path ?? $media->video_path);

        Storage::delete(str_replace('storage/', 'public/', $filePath));
        $media->delete();

        Log::info('Media deleted', [
            'type' => $type,
            'id' => $id,
            'path' => $filePath
        ]);

        return response()->json(['success' => true]);
    }
}
