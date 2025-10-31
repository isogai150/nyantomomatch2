<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class Firewall
{
    /**
     * 管理者ページへのアクセスをIPアドレスで制限するミドルウェア
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // リクエストから取得できる全てのIPアドレスを配列で取得
        // プロキシやCDN(Cloudflare等)を経由している場合、複数のIPが含まれる
        // 例: ["10.17.152.92", "172.64.213.127", "59.147.37.18"]
        $ips = $request->getClientIps();
        
        // 配列の最後の要素を取得 = ユーザーの実際のグローバルIPアドレス
        // end()関数は配列の最後の要素を返す
        // プロキシを経由した場合、最後のIPが実際のクライアントIPになる
        $clientIp = end($ips);
        
        // .envファイルからALLOWED_ADMIN_IPSの値を取得し、カンマで分割して配列化
        // 例: "59.147.37.18,162.120.184.211" → ["59.147.37.18", "162.120.184.211"]
        // 複数のIPアドレスを許可したい場合は、.envでカンマ区切りで指定する
        $allowedIps = explode(',', env('ALLOWED_ADMIN_IPS'));
        
        // クライアントのIPアドレスが許可リストに含まれているかチェック
        // in_array()は配列内に指定した値が存在するか確認する関数
        if (!in_array($clientIp, $allowedIps)) {
            // 許可されていないIPアドレスからのアクセスの場合
            // 403ステータスコードの例外を投げて、Laravelの標準エラーページを表示
            // （以前のJSONレスポンスはコメントアウトで残す）
            
            // return response()->json([
            //     'client_ip' => $clientIp,                    // 検出されたクライアントIP
            //     'getClientIps' => $ips,                      // 取得した全てのIP(デバッグ用)
            //     'allowed_ips' => env('ALLOWED_ADMIN_IPS'),   // 許可されているIP(.envの値)
            //     'app_env' => config('app.env'),              // 現在の環境(production/local等)
            // ], 403); // 403 Forbidden = アクセス権限なし

            abort(403, 'このIPアドレスからのアクセスは許可されていません。');
        }
        
        // IPアドレスが許可リストに含まれている場合は、次の処理へ進む
        // $next($request)でリクエストを次のミドルウェアまたはコントローラーに渡す
        return $next($request);
    }
}
