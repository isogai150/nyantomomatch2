<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * 全てのプロキシを信頼対象にする
     * （Render / Cloudflare 等のプロキシ越しに正しいIPを取得できる）
     */
    protected $proxies = '*'; // 重要修正

    /**
     * X-Forwarded-* ヘッダーを使用して正しいクライアントIPを取得
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL; // 重要修正
}
