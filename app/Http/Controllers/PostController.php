<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\CatPost;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    // 投稿一覧表示
    public function index(Request $request)
    {
        // 基本クエリ（削除済み除外）
        $query = Post::query()->whereNull('deleted_at');

        // 検索（タイトル or 地域）
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('region', 'like', '%' . $request->search . '%');
            });
        }

        // 並び替え
        $sort = $request->get('sort', 'new');

        if ($sort === 'old') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'popular') {
            // お気に入り数の多い順
            $query->withCount('favorites')->orderBy('favorites_count', 'desc');
        } else {
            // 新しい順（デフォルト）
            $query->orderBy('created_at', 'desc');
        }

        // 投稿＋画像リレーション読み込み
        $catposts = $query->with('images')->paginate(10);

        // ビューへ渡す
        return view('home.index', compact('catposts'));
    }

    // 投稿詳細表示
    public function detail(Post $post)
    {
        // 関連データを事前ロード（N+1防止）
        $post->load(['user', 'images', 'videos']);
        // dd($post);

        return view('catpost.detail', compact('post'));
    }

// =================================================================================

    // 自分の投稿一覧表示機能
    public function myCatpost()
    {
        $user = Auth::user();

        // 自分の投稿＋画像を取得
        $myCatposts = Post::with('images')
            ->withCount('favorites')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('authority/catpost.index', compact('myCatposts'));
    }

// =================================================================================

    // 猫の情報投稿作成画面
    public function create()
    {
        $user = Auth::user();
        // dd($user);
        return view('authority/catpost.create', [
            'user' => $user,
        ]);
    }

    // 保存処理機能
    public function store(CatPost $request)
    {
        // まずファイルを一時保存（バリデーション前）
        if ($request->hasFile('image')) {
            $tempImages = session('temp_images', []);
            foreach ($request->file('image') as $imageFile) {
                $path = $imageFile->store('temp/images', 'public');
                $tempImages[] = $path;
            }
            session(['temp_images' => $tempImages]);
        }

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('temp/videos', 'public');
            session(['temp_video' => $path]);
        }

        // 基本バリデーション実行
        $validator = Validator::make($request->all(), (new CatPost)->rules(), (new CatPost)->messages(), (new CatPost)->attributes());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // セッションの一時保存分を考慮したカスタムバリデーション
        $tempImageCount = session()->has('temp_images') ? count(session('temp_images')) : 0;
        $newImageCount = $request->hasFile('image') ? count($request->file('image')) : 0;
        $totalImageCount = $tempImageCount + $newImageCount;

        // 画像が1枚もない場合はエラー
        if ($totalImageCount === 0) {
            return redirect()->back()
                ->withErrors(['image' => '画像を最低1枚選択してください。'])
                ->withInput();
        }

        // 画像が3枚を超える場合はエラー
        if ($totalImageCount > 3) {
            // 超過分を削除
            $excessCount = $totalImageCount - 3;
            $tempImages = session('temp_images', []);

            for ($i = 0; $i < $excessCount; $i++) {
                $removed = array_pop($tempImages);
                if ($removed) {
                    Storage::disk('public')->delete($removed);
                }
            }

            session(['temp_images' => $tempImages]);

            return redirect()->back()
                ->withErrors(['image' => '画像は最大3枚までです。'])
                ->withInput();
        }

        // 動画のチェック
        $hasTempVideo = session()->has('temp_video');
        $hasNewVideo = $request->hasFile('video');

        if ($hasTempVideo && $hasNewVideo) {
            // 新しくアップロードされた動画を削除
            Storage::disk('public')->delete(session('temp_video'));
            session()->forget('temp_video');

            return redirect()->back()
                ->withErrors(['video' => '動画は最大1本までです。'])
                ->withInput();
        }

        // バリデーション済みデータを取得
        $validated = $validator->validated();

        // 入力した内容が「投稿を作成」を通してデータが送信されているか確認
        // dd($validated);

        // データベースの posts テーブルに保存
        $post = new Post();
        $post->fill($validated);
        $post->user_id = Auth::id();
        $post->title = $validated['title'];
        $post->age = $validated['age'];
        $post->gender = $validated['gender'];
        $post->breed = $validated['breed'];
        $post->region = $validated['region'];
        $post->cost = $validated['cost'];
        $post->vaccination = $validated['vaccination'];
        $post->medical_history = $validated['medical_history'];
        $post->description = $validated['description'];
        $post->start_date = $validated['start_date'];
        $post->end_date = $validated['end_date'];
        $post->status = $validated['status'] ?? 0; // ステータスの初期値
        $post->save();

        // 一時保存された画像を本保存に移動
        if (session()->has('temp_images')) {
            foreach (session('temp_images') as $tempPath) {
                $newPath = str_replace('temp/images', 'post_images', $tempPath);
                Storage::disk('public')->move($tempPath, $newPath);

                $post->images()->create([
                    'image_path' => 'storage/' . $newPath
                ]);
            }
            session()->forget('temp_images');
        }

        // 新規アップロードされた画像を保存
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $imageFile) {
                $path = $imageFile->store('public/post_images');
                $post->images()->create([
                    'image_path' => str_replace('public/', 'storage/', $path)
                ]);
            }
        }

        // 一時保存動画を本保存に移動
        if (session()->has('temp_video')) {
            $tempPath = session('temp_video');
            $newPath = str_replace('temp/videos', 'post_videos', $tempPath);
            Storage::disk('public')->move($tempPath, $newPath);

            $post->videos()->create([
                'video_path' => 'storage/' . $newPath
            ]);
            session()->forget('temp_video');
        }

        // 新規アップロードされた動画を保存
        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            $path = $videoFile->store('public/post_videos');
            $post->videos()->create([
                'video_path' => str_replace('public/', 'storage/', $path)
            ]);
        }

        // 成功時はセッションをクリア
        session()->forget(['temp_images', 'temp_video']);

        return redirect()->route('catpost.index')->with('success', '投稿が作成されました！');
    }

    // 一時ファイルのクリーンアップ
    private function cleanupTempFiles()
    {
        if (session()->has('temp_images')) {
            foreach (session('temp_images') as $path) {
                Storage::disk('public')->delete($path);
            }
            session()->forget('temp_images');
        }

        if (session()->has('temp_video')) {
            Storage::disk('public')->delete(session('temp_video'));
            session()->forget('temp_video');
        }
    }

    // 一時保存ファイル削除用
    public function deleteTempImage($index)
    {
        $tempImages = session('temp_images', []);
        if (isset($tempImages[$index])) {
            Storage::disk('public')->delete($tempImages[$index]);
            unset($tempImages[$index]);
            session(['temp_images' => array_values($tempImages)]);
        }
        return response()->json(['success' => true]);
    }

    public function deleteTempVideo()
    {
        if (session()->has('temp_video')) {
            Storage::disk('public')->delete(session('temp_video'));
            session()->forget('temp_video');
        }
        return response()->json(['success' => true]);
    }

// =================================================================================

    // 画像のアップロード
    public function image(Request $request)
    {

        // ディレクトリ名を任意の名前で設定します
        $dir = 'img';

        // imgディレクトリを作成し画像を保存
        // storage/app/public/任意のディレクトリ名/
        $request->file('image')->store('public/' . $dir);

        // ページを更新
        return redirect('/');

        $image = new User();

        // $任意の変数名　=　テーブルを操作するモデル名();
        // storage/app/public/任意のディレクトリ名/
        $image->post_id = $file_name;
        $image->post_id = 'storage/app/public/' . $dir . '/' . $file_name;
        $image->save();

    //ページを更新
    return redirect('/');
    }

// =================================================================================

    // 猫の投稿編集
    public function createedit()
    {
        $user = Auth::user();
        // dd($user);
        return view('authority/catpost.edit', [
            'user' => $user,
        ]);
    }

// =================================================================================

// 編集画面表示
public function edit($id)
{
    $post = Post::with(['images', 'videos'])->findOrFail($id);
    $maxImages = 3;
    $currentImageCount = $post->images->count();
    $remainingImageSlots = max(0, $maxImages - $currentImageCount);

    return view('authority.catpost.edit', compact('post', 'remainingImageSlots'));
}

// 編集内容更新
public function update(CatPost $request, Post $post)
{
    $user = Auth::user();

    if ($post->user_id !== $user->id) {
        abort(403, 'この投稿を編集する権限がありません。');
    }

    // ★★★ ファイルサイズの追加チェック ★★★
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $imageFile) {
            if ($imageFile->getSize() > 2 * 1024 * 1024) {
                return back()->withErrors(['images' => '画像は2MB以下にしてください。'])->withInput();
            }
        }
    }

    // バリデーション済みデータ
    $validated = $request->validated();


    // 投稿内容更新
    $post->fill($validated);
    $post->save();

    // 画像更新処理（新しい画像がアップロードされた場合のみ）
    if ($request->hasFile('images')) {
        // 最大3枚までのチェック
        $currentImageCount = $post->images->count();
        $newImageCount = count($request->file('images'));

        if ($currentImageCount + $newImageCount > 3) {
            return back()->withErrors(['images' => '画像は最大3枚までです。']);
        }

        // 新しい画像を保存（既存画像は削除しない）
        foreach ($request->file('images') as $imageFile) {
            $path = $imageFile->store('public/post_images');
            $post->images()->create([
                'image_path' => str_replace('public/', 'storage/', $path)
            ]);
        }
    }

    // 動画更新処理
    if ($request->hasFile('video')) {
        // 既存動画を削除
        foreach ($post->videos as $video) {
            Storage::delete(str_replace('storage/', 'public/', $video->video_path));
            $video->delete();
        }

        $videoFile = $request->file('video');
        $path = $videoFile->store('public/post_videos');
        $post->videos()->create([
            'video_path' => str_replace('public/', 'storage/', $path)
        ]);
    }
    return redirect()->route('mycatpost.index')->with('success', '投稿が更新されました！');
}

// ==================================================

public function deleteMedia($type, $id)
{
    // dd($type, $id);
    if ($type === 'image') {
        $media = \App\Models\PostImage::findOrFail($id);
    } elseif ($type === 'video') {
        $media = \App\Models\PostVideo::findOrFail($id);
    } else {
        abort(400, '無効なタイプです。');
    }

    // ファイル削除
    Storage::delete(str_replace('storage/', 'public/', $media->image_path ?? $media->video_path));
    $media->delete();

    return response()->json(['success' => true]);
}


// =======================================================================

// 投稿削除処理
public function destroy(Post $post)
{
    $user = Auth::user();

    // 投稿者本人かチェック
    if ($post->user_id !== $user->id) {
        abort(403, 'この投稿を削除する権限がありません。');
    }

    // 画像ファイルを削除
    foreach ($post->images as $image) {
        Storage::delete(str_replace('storage/', 'public/', $image->image_path));
        $image->delete();
    }

    // 動画ファイルを削除
    foreach ($post->videos as $video) {
        Storage::delete(str_replace('storage/', 'public/', $video->video_path));
        $video->delete();
    }

    // 投稿を削除
    $post->delete();

    return redirect()->route('mycatpost.index')->with('success', '投稿を削除しました。');
}

}
