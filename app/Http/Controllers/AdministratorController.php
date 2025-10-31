<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pair;
use App\Models\Message;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\Authority;
use App\Models\PostReport;
use App\Models\MessageReport;
use App\Models\Transfer;
use Illuminate\Support\Facades\Storage;

class AdministratorController extends Controller
{
    // 管理者ログイン関連
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors(['email' => 'ログイン情報が一致しません。']);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }

    // ダッシュボード
    public function index()
    {
        $userCount = User::count();
        $dmCount = Pair::count();
        $messageCount = Message::count();
        $postCount = Post::count();

        $currentYear = date('Y');

        $monthlyUserCounts = User::selectRaw('EXTRACT(MONTH FROM created_at) AS month, COUNT(*) AS count')
            ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [$currentYear])
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

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
            'userCount', 'dmCount', 'messageCount', 'postCount',
            'userData', 'postData'
        ));
    }

    // 投稿権限申請関連
    public function authorityList()
    {
        $authoritys = Authority::whereNotIn('status', [1, 2])->get();
        return view('admin.authority.index', compact('authoritys'));
    }

    public function authorityCancel($id)
    {
        $authority = Authority::findOrFail($id);
        $authority->status = 2;
        $authority->save();
        return redirect()->route('admin.authority');
    }

    public function AuthorityApproval($id)
    {
        $authority = Authority::findOrFail($id);
        $authority->status = 1;
        $authority->save();

        // 権限付与
        $user = $authority->user;
        $user->role = 1;
        $user->save();

        return redirect()->route('admin.authority');
    }

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
        $dm = Pair::with(['userA', 'userB'])->findOrFail($id);
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

    // 管理者用投稿削除
public function postDestroy($post)
{
    $postModel = Post::findOrFail($post);

    // 関連メディアも削除（必要に応じて）
    foreach ($postModel->images as $image) {
        Storage::disk(config('filesystems.default'))->delete('post_images/' . $image->image_path);
    }
    foreach ($postModel->videos as $video) {
        Storage::disk(config('filesystems.default'))->delete('post_videos/' . $video->video_path);
    }

    $postModel->delete();

    return redirect()->route('admin.post.reports')->with('success', '投稿を削除しました');
}


    public function postReportResolve($id)
    {
        $report = PostReport::findOrFail($id);
        $report->update(['status' => 1]);
        return redirect()->route('admin.post.reports');
    }

    public function postReportReject($id)
    {
        $report = PostReport::findOrFail($id);
        $report->update(['status' => 2]);
        return redirect()->route('admin.post.reports');
    }

    // ユーザーBAN
    public function userBan($id)
    {
        $user = User::findOrFail($id);
        $user->is_banned = 1;
        $user->save();
        return redirect()->back();
    }

    // BAN解除
    public function userUnban($id)
    {
        $user = User::findOrFail($id);
        $user->is_banned = 0;
        $user->save();
        return redirect()->back();
    }

    // DM通報一覧表示
    public function dmReportList()
    {
        $reports = MessageReport::with('user')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.report.dm.index', compact('reports'));
    }

    public function dmReportResolve($id)
    {
        $report = MessageReport::findOrFail($id);
        $report->status = 1;
        $report->save();
        return redirect()->route('admin.report');
    }

    public function dmReportReject($id)
    {
        $report = MessageReport::findOrFail($id);
        $report->status = 2;
        $report->save();
        return redirect()->route('admin.report');
    }

    // DM通報詳細表示
    public function dmReportDetail($id)
    {
        $report = MessageReport::with('user')->findOrFail($id);
        return view('admin.report.dm.detail', compact('report'));
    }

    // メッセージ削除
public function messageDestroy(Request $request, $dm, $message)
{
    $messageModel = Message::findOrFail($message);
    $messageModel->delete();

    if ($request->input('from') === 'report_detail') {
        // DM通報詳細ページに戻る
        $report = MessageReport::where('message_id', $message)->first();
        if ($report) {
            return redirect()
                ->route('admin.report.detail', ['id' => $report->id])
                ->with('success', 'メッセージを削除しました');
        }
    }

    // 通常はDM一覧に戻す
    return redirect()
        ->route('admin.dm.detail', ['dm' => $dm])
        ->with('success', 'メッセージを削除しました');
}

    // ユーザー一覧
    public function userList()
    {
        $users = User::orderBy('id')->get();
        return view('admin.user.index', compact('users'));
    }

    // ユーザー詳細
public function userDetail($id)
{
    $user = User::findOrFail($id);

    // 投稿数
    $postCount = Post::where('user_id', $user->id)->count();

    // メッセージ送信数 ← 修正！
    $messageCount = Message::where('user_id', $user->id)->count();

    // 通報数
$dmReportCount = MessageReport::where('user_id', $user->id)->count();

$postReportCount = PostReport::whereHas('post', function ($q) use ($user) {
    $q->where('user_id', $user->id);
})->count();

$reportCount = $dmReportCount + $postReportCount;

    return view('admin.user.detail', compact(
        'user',
        'postCount',
        'messageCount',
        'reportCount'
    ));
}

// 譲渡成立一覧表示
public function transferList()
{
    $transfers = Transfer::orderBy('id')->get();
        return view('admin.transfer.index', compact('transfers'));
}

// 管理者退会
public function destroy(Request $request)
{
    $admin = Auth::guard('admin')->user();
    $admin->delete();
    Auth::guard('admin')->logout();

    return redirect('/admin/login')->with('success', '退会が完了しました');
}

}
