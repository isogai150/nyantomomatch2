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
        $user->name = $request->name;
        $user->email = $request->email;
        $user->description = $request->description;

        // パスワードが入力されている場合のみ更新
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->filled('image_path')) {
            $user->image_path = $request->image_path;
        }

        $user->save();

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