<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * パスワードリセット成功時の処理
     */
    protected function sendResetResponse($response)
    {
        // 成功メッセージをセッションに保存
        return redirect()->route('login')->with('reset_success', true);
    }
}
