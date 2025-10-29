<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class Firewall
{
    public function handle(Request $request, Closure $next)
    {
        /**
         * 本番環境のみ IP制限を有効化
         *
         * - 開発環境(local)では制限なし
         * - デプロイ後に学校IPだけアクセス可 → 家ではアクセス不可
         */
        if (app()->environment('production')) {

            /**
             * 許可IPの設定参照先を変更
             * env('ALLOWED_ADMIN_IPS') → .env から直接取得する方式へ
             * Render等の環境変数で簡単に管理できる
             */
            $allowedIps = explode(',', env('ALLOWED_ADMIN_IPS', ''));

            /**
             * 実際のアクセス元IPを取得
             * getClientIps() を使い、一番信頼できる値を最後に取得
             */
            $clientIp = collect($request->getClientIps())->last();

            /**
             * 許可IPに一致しない場合は403エラー
             */
            if (!IpUtils::checkIp($clientIp, $allowedIps)) {
                abort(403, 'このIPアドレスからのアクセスは許可されていません。');
            }
        }

        // 次のミドルウェアへ処理を渡す
        return $next($request);
    }
}
