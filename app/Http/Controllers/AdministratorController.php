<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pair;
use App\Models\Message;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\Authority;
use Database\Seeders\Message_reports;
use Illuminate\Support\Facades\DB;
use App\Models\PostReport;
use App\Models\MessageReport;

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

        $currentYear = date('Y');

        // 月別ユーザー登録数
        // EXTRACT()は年月を取り出す関数
        $monthlyUserCounts = User::selectRaw('EXTRACT(MONTH FROM created_at) AS month, COUNT(*) AS count')
            ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [$currentYear])
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // 月別投稿数
        $monthlyPostCounts = Post::selectRaw('EXTRACT(MONTH FROM created_at) AS month, COUNT(*) AS count')
            ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [$currentYear])
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
    // 投稿権限申請詳細表示
    public function authorityDetail($id)
    {
        $authority = Authority::findOrFail($id);

        return view('admin.authority.detail', compact('authority'));
    }

    // DM一覧表示
    public function dmList()
    {
        $dms = Pair::whereNull('deleted_at')->get();

        return view('admin.dm.index', compact('dms'));
    }

    // DM詳細表示
    public function detail($id)
    {
        // DBのpairsテーブルのid(マッチしたグループ)番号を取得している
        // dd($id);

        // 指定されたDMを取得（userA、userBをEager Loadingで取得）
        $dm = Pair::with(['userA', 'userB'])->findOrFail($id);

        // そのDMに関連するメッセージを取得（古い順）
        $messages = Message::where('pair_id', $id)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.dm.detail', compact('dm', 'messages'));
    }

    // 投稿通報一覧表示
    public function postReports()
    {
        $reports = PostReport::with(['user', 'post'])
            ->orderBy('status')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.report.post.index', compact('reports'));
    }

    // 投稿通報詳細表示
    public function postReportDetail($id)
    {
        $report = PostReport::with(['user', 'post'])->findOrFail($id);

        return view('admin.report.post.detail', compact('report'));
    }

    // 通報対応済み更新
    public function postReportResolve($id)
    {
        $report = PostReport::findOrFail($id);
        $report->update(['status' => 1]);

        return redirect()->route('admin.post.reports')->with('success', '対応済みにしました');
    }

    // 通報却下更新
    public function postReportReject($id)
    {
        $report = PostReport::findOrFail($id);
        $report->update(['status' => 2]);

        return redirect()->route('admin.post.reports')->with('success', '通報を却下しました');
    }

    // ユーザーBAN
    public function userBan($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_banned' => 1]);

        return redirect()->back()->with('success', 'ユーザーをBANしました');
    }

    // DM通報一覧
    public function dmReportList()
    {
        // メッセージ通報情報（message_reports）テーブル
        $reports = MessageReport::with('user')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.report.dm.index', compact('reports'));
    }

    // DM通報を解決済みにする
    public function dmReportResolve($id)
    {
        $report = MessageReport::findOrFail($id);
        $report->status = 1;
        $report->save();

        return redirect()->route('admin.report')->with('success', '通報を解決済みにしました。');
    }

    // DM通報を却下する
    public function dmReportReject($id)
    {
        $report = MessageReport::findOrFail($id);
        $report->status = 2;
        $report->save();

        return redirect()->route('admin.report')->with('success', '通報を却下しました。');
    }

    // DM通報詳細表示
    public function dmReportDetail($id)
    {
        $report = MessageReport::with('user')->findOrFail($id);
        return view('admin.report.dm.detail', compact('report'));
    }

// メッセージ削除（管理者用）
public function messageDestroy($dm, $message)
{
    $messageModel = Message::findOrFail($message);

    // 削除処理
    $messageModel->delete();

    return redirect()->route('admin.report')->with('success', 'メッセージを削除しました');
}
}

