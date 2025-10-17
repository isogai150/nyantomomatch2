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

        return view('admin.dashboard.index', compact('userCount', 'dmCount', 'messageCount', 'postCount'));
    }
}
