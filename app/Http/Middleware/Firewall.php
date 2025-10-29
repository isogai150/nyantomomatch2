<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class Firewall
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->environment('production')) {

            // .env に設定した許可IP一覧
            $allowedIps = explode(',', env('ALLOWED_ADMIN_IPS', ''));

            // CloudFlare を経由した本当のクライアントIPを取得
            $clientIp = $request->header('CF-Connecting-IP')
                ?: collect($request->getClientIps())->last()
                ?: $request->ip();

            // ★デバッグ表示は消してOK（必要なら残す）
            // logger("Client IP: " . $clientIp);

            if (!IpUtils::checkIp(trim($clientIp), $allowedIps)) {
                abort(403, 'このIPアドレスからのアクセスは許可されていません。');
            }
        }

        return $next($request);
    }
}
