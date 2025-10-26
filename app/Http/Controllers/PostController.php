<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\CatPost;
use Illuminate\Support\Facades\Storage;

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
// 保存処理機能
public function store(CatPost $request)
{
    // バリデーション済みデータを取得
    $validated = $request->validated();

    // ディスク取得（local or s3）
    $disk = config('filesystems.default');

    // データベースの posts テーブルに保存
    $post = new Post();
    $post->fill($validated);
    $post->user_id = Auth::id();
    $post->save();

    // 画像保存処理
    if ($request->hasFile('image')) {
        $images = $request->file('image');
        
        // 配列でない場合は配列に変換
        if (!is_array($images)) {
            $images = [$images];
        }
        
        // 最大3枚まで処理
        $imageCount = 0;
        foreach ($images as $imageFile) {
            if ($imageCount >= 3) {
                break;
            }
            
            if ($imageFile && $imageFile->isValid()) {
                $fileName = uniqid() . '.' . $imageFile->getClientOriginalExtension();
                $path = $imageFile->storeAs('post_images', $fileName, $disk);

                $post->images()->create([
                    'image_path' => $fileName
                ]);
                
                $imageCount++;
            }
        }
    }

    // 動画保存処理
    if ($request->hasFile('video')) {
        $videoFile = $request->file('video');
        if ($videoFile->isValid()) {
            $videoName = uniqid() . '.' . $videoFile->getClientOriginalExtension();
            $path = $videoFile->storeAs('post_videos', $videoName, $disk);

            $post->videos()->create([
                'video_path' => $videoName
            ]);
        }
    }

    // 投稿成功時にセッションストレージをクリアするためのフラグを追加
    return redirect()->route('catpost.index')->with('success', '投稿が作成されました！')->with('clear_storage', true);
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

public function update(CatPost $request, Post $post)
{
    $user = Auth::user();

    if ($post->user_id !== $user->id) {
        abort(403, 'この投稿を編集する権限がありません。');
    }

    // ★★★ 既存画像数と新規画像数の合計をチェック ★★★
    $currentImageCount = $post->images->count();
    $newImageCount = $request->hasFile('images') ? count($request->file('images')) : 0;
    $totalImageCount = $currentImageCount + $newImageCount;

    // ★★★ 画像が1枚もない場合はエラー ★★★
    if ($totalImageCount === 0) {
        return back()->withErrors(['images' => '最低1枚の画像を選択してください。'])->withInput();
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

    $disk = config('filesystems.default'); // local or s3

    // 画像更新処理（新しい画像がアップロードされた場合のみ）
    if ($request->hasFile('images')) {
        // 最大3枚までのチェック
        if ($totalImageCount > 3) {
            return back()->withErrors(['images' => '画像は最大3枚までです。']);
        }

        // 新しい画像を保存（既存画像は削除しない）
        foreach ($request->file('images') as $imageFile) {
            $fileName = uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $path = $imageFile->storeAs('post_images', $fileName, $disk);
            $post->images()->create([
                'image_path' => $fileName
            ]);
        }
    }

    // 動画更新処理
    if ($request->hasFile('video')) {
        // 既存動画を削除
        foreach ($post->videos as $video) {
            Storage::disk($disk)->delete('post_videos/' . $video->video_path);
            $video->delete();
        }

        $videoFile = $request->file('video');
        $videoName = uniqid() . '.' . $videoFile->getClientOriginalExtension();
        $videoPath = $videoFile->storeAs('post_videos', $videoName, $disk);

        $post->videos()->create([
            'video_path' => $videoName
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


}
