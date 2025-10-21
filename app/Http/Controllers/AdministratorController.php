<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pair;
use App\Models\Message;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\Authority;

class AdministratorController extends Controller
{

// ログインフォーム表示機能
 public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    // ログイン承認機能
    public function login(Request $request)
    {
        // dd($request);
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors(['email' => 'ログイン情報が一致しません。']);
    }

    // ログアウト機能
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }

    // 管理者topページ（ダッシュボードの表示）
public function index()
{
    $userCount = User::count();
    $dmCount = Pair::count();
    $messageCount = Message::count();
    $postCount = Post::count();

    // ▼ 月別ユーザー登録数
    $monthlyUserCounts = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->pluck('count', 'month')
        ->toArray();

    // ▼ 月別投稿数
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

// 投稿申請一覧表示機能
public function authorityList()
{
    // 申請中のみの申請一覧を取得
    $authoritys = Authority::whereNotIn('status', [1, 2])->get();

    return view('admin.authority.index', compact('authoritys'));
}

// 投稿申請キャンセル処理
public function authorityCancel($id)
{
    $authority = Authority::findOrFail($id);

    // 申請キャンセル
    $authority->status = 2;
    $authority->save();

    return redirect()->route('admin.authority');
}

// 投稿申請承認処理
public function AuthorityApproval($id)
{
    $authority = Authority::findOrFail($id);

    // 申請承認
    $authority->status = 1;
    $authority->save();

    // ユーザーの権限付与
    $user = $authority->user;
    $user->role = 1;
    $user->save();

    return redirect()->route('admin.authority');
}

// 投稿権限申請詳細表示
public function authorityDetail($id)
{
    $authoritys = Authority::findOrFail($id)->get();

    return view('admin.authority.detail', compact('authoritys'));
}

}
