<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{

    // 投稿一覧表示
    // 検索フォーム、並び替え、ページネーション対応
    // 「削除済み（SoftDelete）」は除外
    // 投稿に紐づく画像も一緒に取得（with）
    public function index(Request $request)
    {
        //基本クエリ作成（ソフトデリート除外）
        $query = Post::query()->whereNull('deleted_at');

        // 検索機能
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('region', 'like', '%' . $request->search . '%');
            });

            // dd($request->search, $query->toSql(), $query->getBindings());
        }

        // 並び替え機能
        $sort = $request->get('sort', 'new');

        if ($sort === 'old') {
            // 古い順
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'popular') {
            // 人気順（お気に入り数をカウントして並べ替え）
            $query->withCount('favorites')->orderBy('favorites_count', 'desc');
        } else {
            // 新しい順
            $query->orderBy('created_at', 'desc');
        }

        $catposts = $query->with('images')->paginate(10);

        // dd($catposts);

        // ビューへ渡す
        return view('home.index', compact('catposts'));
    }

    // 投稿詳細ページ表示
    // ルートモデルバインディングによりPostモデルを自動取得
    public function show(Post $post)
    {
        // dd($post);

        return view('posts.show', compact('post'));
    }

        // 投稿一覧
    public function index(Request $request)
    {
        $query = Post::query()->whereNull('deleted_at');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('region', 'like', '%' . $request->search . '%');
            });
        }

        $sort = $request->get('sort', 'new');
        if ($sort === 'old') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'popular') {
            $query->withCount('favorites')->orderBy('favorites_count', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $catposts = $query->with('images')->paginate(10);
        return view('home.index', compact('catposts'));
    }

    // 投稿詳細
    public function show($id)
    {
        $post = Post::with(['user', 'images', 'videos'])->findOrFail($id);
        // dd($post); // ← データ確認用

        return view('posts.show', compact('post'));
    }
}
