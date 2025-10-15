<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PaymentController extends Controller
{
    // カート情報表示機能
    public function showcart($postId)
    {
        $post = Post::findOrFail($postId);

        return view('payment.cart', compact('post'));
    }

    // 決済情報入力ページ表示機能
    public function showForm($postId)
    {
        $post = Post::findOrFail($postId);

        return view('payment.settlement', compact('post'));
    }
}
