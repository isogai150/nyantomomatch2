<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pair;
use App\Models\Message;
use App\Models\Post;

class AdministratorController extends Controller
{
    // 管理者topページ（ダッシュボードの表示）
public function index()
{
    $userCount = User::count();
    $dmCount = Pair::count();
    $messageCount = Message::count();
    $postCount = Post::count();

    // 月別ユーザー登録数
    $monthlyUserCounts = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->pluck('count', 'month')
        ->toArray();

    // 月別投稿数
    $monthlyPostCounts = Post::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->pluck('count', 'month')
        ->toArray();

    $months = range(1, 12);
    $userData = [];
    $postData = [];

    foreach ($months as $m) {
        $userData[] = $monthlyUserCounts[$m] ?? 0;
        $postData[] = $monthlyPostCounts[$m] ?? 0;
    }

    return view('admin.dashboard.index', compact(
        'userCount',
        'dmCount',
        'messageCount',
        'postCount',
        'userData',
        'postData'
    ));
}

}
