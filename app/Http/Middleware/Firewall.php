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

            // envから許可IP一覧を取得（Renderで管理）
            $allowedIps = array_map('trim', explode(',', env('ALLOWED_ADMIN_IPS', '')));

            // Cloudflare経由の場合は getClientIps の最後が正しいIP
            $clientIp = collect($request->getClientIps())->last();

            // デバッグログ（必要なければ削除）
            // logger('Client IP: ' . $clientIp);

            if (!IpUtils::checkIp($clientIp, $allowedIps)) {
                abort(403, 'このIPアドレスからのアクセスは許可されていません。');
            }
        }

        return $next($request);
    }
}
