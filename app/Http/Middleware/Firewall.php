<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class Firewall
{
    // public function handle(Request $request, Closure $next)
    // {
    //     // 本番環境のみIP制限
    //     if (app()->environment('production')) {

    //         // 許可IPを .env から取得
    //         $allowedIps = explode(',', env('ALLOWED_ADMIN_IPS', ''));

    //         // 最後のIPがユーザーの実IP
    //         $clientIp = collect($request->getClientIps())->last();

    //         // デバッグ表示が必要な場合のみ
    //         // \Log::info('Client IP check', [
    //         //     'clientIp' => $clientIp,
    //         //     'allowedIps' => $allowedIps
    //         // ]);

    //         // 許可されていなければ 403
    //         if (!IpUtils::checkIp($clientIp, $allowedIps)) {
    //             abort(403, 'このIPアドレスからのアクセスは許可されていません。');
    //         }
    //     }

    //     return $next($request);
    // }

    public function handle($request, Closure $next)
    {
        // X-Forwarded-For経由の最後のIPを取得
        $ips = $request->getClientIps();
        $clientIp = end($ips); // 最後の要素を取得

        $allowedIps = explode(',', env('ALLOWED_ADMIN_IPS'));

        if (!in_array($clientIp, $allowedIps)) {
            return response()->json([
                'client_ip' => $clientIp,
                'getClientIps' => $ips,
                'allowed_ips' => env('ALLOWED_ADMIN_IPS'),
                'app_env' => config('app.env'),
            ], 403);
        }

        return $next($request);
    }
}
