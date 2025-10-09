<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\User\EditUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // マイページ表示
    public function index()
    {
        $user = Auth::user();
        // dd($user);
        return view('mypage/index', [
            'user' => $user,
        ]);
    }

    // マイページ更新
    public function edit(User $user, EditUser $request)
    {

        $user = Auth::user();

        // 更新データを準備
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'description' => $request->description,
        ];

        // パスワードが入力されている場合のみ更新
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('mypage.index', [
            'user' => $user
        ]);
    }

    // ユーザー退会処理
    public function withdraw(Request $request)
    {
        $user = Auth::user();

        // 論理削除（deleted_atに現在時刻をセット）
        $user->delete();

        // ログアウト処理
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // トップページに遷移
        return redirect('/')->with('status', '退会処理が完了しました。');
    }
}