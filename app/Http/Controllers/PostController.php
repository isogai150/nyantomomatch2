<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * 投稿一覧表示
     * - ソフトデリート除外
     * - 検索機能（タイトル・地域）
     * - 並び替え（新着順・古い順・人気順）
     * - 画像とのリレーション読み込み
     * - ページネーション
     *
     * 対応URL:
     * GET /catpost
     * GET /catpost?search=xxx
     * GET /catpost?sort=xxx
     */
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

    /**
     * 投稿詳細表示
     * - ルートモデルバインディング対応
     * - 投稿・ユーザー・画像・動画を同時に取得
     *
     * 対応URL:
     * GET /catpost/{catpost}
     */
    public function show(Post $post)
    {
        // 関連データを事前ロード（N+1防止）
        $post->load(['user', 'images', 'videos']);

        return view('posts.show', compact('post'));
    }
}
