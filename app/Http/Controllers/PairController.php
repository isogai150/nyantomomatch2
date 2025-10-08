<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pair;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;

class PairController extends Controller
{

    public function index()
    {
        $userId = Auth::id();

        // ログインユーザーが関わるペアを取得
        $pairs = Pair::where('userA_id', $userId)
                    ->orWhere('userB_id', $userId)
                    ->get();

        $conversationUsers = [];

        foreach ($pairs as $pair) {
            // 相手のユーザーIDを特定
            $otherUserId = $pair->userA_id == $userId ? $pair->userB_id : $pair->userA_id;
            $otherUser = User::find($otherUserId);

            if (!$otherUser) continue;

            // このペアのメッセージを取得
            $messages = Message::where('pair_id', $pair->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

            if ($messages->isEmpty()) continue;

            // 最後のメッセージ
            $lastMessage = $messages->first();

            // 時間差を計算
            $timeAgo = $this->getTimeAgo($lastMessage->updated_at);

            $conversationUsers[] = [
                'user' => $otherUser,
                'pair_id' => $pair->id,
                'last_message' => $lastMessage->content,
                'time_ago' => $timeAgo,
                'message_count' => $messages->count(),
                'last_message_time' => $lastMessage->updated_at,
            ];
        }

        // 最新のメッセージ順にソート
        usort($conversationUsers, function($a, $b) {
            return $b['last_message_time'] <=> $a['last_message_time'];
        });

        return view('dm.index', compact('conversationUsers'));
    }

    private function getTimeAgo($datetime)
    {
        $now = Carbon::now();
        $messageTime = Carbon::parse($datetime);

        $diffInMinutes = $now->diffInMinutes($messageTime);
        $diffInHours = $now->diffInHours($messageTime);
        $diffInDays = $now->diffInDays($messageTime);

        if ($diffInMinutes < 1) {
            return 'たった今';
        } elseif ($diffInMinutes < 60) {
            return $diffInMinutes . '分前';
        } elseif ($diffInHours < 24) {
            return $diffInHours . '時間前';
        } elseif ($diffInDays < 7) {
            return $diffInDays . '日前';
        } elseif ($diffInDays < 30) {
            return floor($diffInDays / 7) . '週間前';
        } elseif ($diffInDays < 365) {
            return floor($diffInDays / 30) . 'ヶ月前';
        } else {
            return floor($diffInDays / 365) . '年前';
        }
    }
}

