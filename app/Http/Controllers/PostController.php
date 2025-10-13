<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

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

}
