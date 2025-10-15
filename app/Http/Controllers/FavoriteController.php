<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Post $post)
    {
        $user = auth()->user();

        if ($user->favoritePosts()->where('post_id', $post->id)->exists()) {
            // 既にお気に入り → 解除
            $user->favoritePosts()->detach($post->id);
        } else {
            // 未登録 → 登録
            $user->favoritePosts()->attach($post->id);
        }

        return back();
    }


    // お気に入り一覧表示
    public function index()
    {
        // ログインユーザーのお気に入り投稿を取得（Eager Loading使用）
        $catposts = Auth::user()->favoritePosts()
            ->withPivot('created_at')
            ->with([
                'images' => function ($query) {
                    // 最初の画像のみ取得（パフォーマンス向上）
                    $query->orderBy('id', 'asc')->limit(1);
                },
                'user:id,name,image_path' // 投稿者情報（必要な場合のみ）
            ])
            ->where('status', '!=', 2) // 譲渡済みを除外する
            ->orderByPivot('created_at', 'desc') // お気に入り登録日が新しい順
            ->paginate(12); // 3列×4行 = 12件表示

        return view('favorite.index', compact('catposts'));
    }

}

