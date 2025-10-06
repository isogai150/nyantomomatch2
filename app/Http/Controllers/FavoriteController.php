<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

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
}

