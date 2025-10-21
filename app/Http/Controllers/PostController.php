<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\CatPost;

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
        // バリデーション済みデータを取得
        $validated = $request->validated();

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

        // 画像保存処理
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $imageFile) {
                $path = $imageFile->store('public/post_images'); // storage/app/public/post_images に保存
                $post->images()->create([
                    'image_path' => str_replace('public/', 'storage/', $path) // 公開パスに変換
            ]);
        }
    }

        // 動画保存処理
        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            $path = $videoFile->store('public/post_videos');
            $post->videos()->create([
                'video_path' => str_replace('public/', 'storage/', $path)
            ]);
        }

    return redirect()->route('catpost.index')->with('success', '投稿が作成されました！');
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
}
