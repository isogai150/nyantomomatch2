<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/';

    /**
     * パスワードリセット成功後の処理をオーバーライド
     */
    protected function sendResetResponse(Request $request, $response)
    {
        // 成功時はパスワードリセットページに戻り、成功フラグを付与
        return redirect()->route('password.reset', ['token' => $request->token])
            ->with('password_reset_success', true);
    }
}