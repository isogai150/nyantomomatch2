<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Block;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{

    // ブロック登録
    public function store($blockedId)
    {
        $user = Auth::user();

        // 自分自身をブロックできないように
        if ($user->id === (int) $blockedId) {
            return back()->with('warning', '自分をブロックすることはできません。');
        }

        // すでにブロック済みならスキップ
        $exists = Block::where('blocker_id', $user->id)
            ->where('blocked_id', $blockedId)
            ->exists();

        if ($exists) {
            return back()->with('warning', 'すでにこのユーザーをブロックしています。');
        }

        Block::create([
            'blocker_id' => $user->id,
            'blocked_id' => $blockedId,
        ]);

        return back()->with('success', 'ユーザーをブロックしました。');
    }

    // ブロック解除
    public function destroy($blockedId)
    {
        $user = Auth::user();

        // 該当ブロックが存在するか確認して削除
        $block = Block::where('blocker_id', $user->id)
            ->where('blocked_id', $blockedId)
            ->first();

        if (!$block) {
            return back()->with('warning', 'ブロック情報が見つかりません。');
        }

        $block->delete();

        return back()->with('success', 'ブロックを解除しました。');
    }
}
