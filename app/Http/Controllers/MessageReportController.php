<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MessageReport;

class MessageReportController extends Controller
{
public function store($dm, $message)
{
    // 重複通報チェック
    if (MessageReport::where('user_id', auth()->id())
        ->where('pair_id', $dm)
        ->where('message_id', $message)
        ->exists()) {
        return back()->with('warning', 'すでに通報済みです');
    }

    MessageReport::create([
        'user_id' => auth()->id(),
        'pair_id' => $dm,
        'message_id' => $message,
        'status' => 0
    ]);

    return back()->with('success', '通報しました');
}

}
