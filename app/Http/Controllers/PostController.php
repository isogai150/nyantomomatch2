<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    // 一覧
public function index(Request $request)
{
    // ソフトデリート除外
    $query = Post::query()->whereNull('deleted_at');

    // 検索
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
        $query->withCount('favorites')->orderBy('favorites_count', 'desc');
    } else {
        $query->orderBy('created_at', 'desc');
    }

    // 画像リレーションも取得
    $catposts = $query->with('images')->paginate(10);

    return view('home.index', compact('catposts'));
}


    // 詳細
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }
}
