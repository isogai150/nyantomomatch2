<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// IPアドレスをあ扱うためのuse文
use Symfony\Component\HttpFoundation\IpUtils;

class Firewall
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 本番環境のみ有効化
        // app()->environment('production')はアプリケーションが本番環境かどうかを判定するために使用されるヘルパー関数
        if (app()->environment('production')) {

            // if(true) {
                // 環境変数から許可IPを取得
                $allowedIps = explode(',', config('firewall.allowed_ips'));
                $clientIp = collect($request->getClientIps())->last();

                // 一致しなかったら403エラー
                if (!IpUtils::checkIp($clientIp, $allowedIps)) {
                    abort(403, 'このIPアドレスからのアクセスは許可されていません。');
            }
        }

        return $next($request);
    }
}
