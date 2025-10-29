<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostReport;

class PostReportController extends Controller
{
            public function store($postId)
    {
        // 二重通報防止
        if (PostReport::where('user_id', auth()->id())
            ->where('post_id', $postId)
            ->exists()) {
            return back()->with('warning', 'すでに通報済みです');
        }

        PostReport::create([
            'user_id' => auth()->id(),
            'post_id' => $postId,
            'status' => 0, // 未対応
        ]);

        return back()->with('success', '通報しました');
    }
}
