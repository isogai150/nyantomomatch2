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
}
