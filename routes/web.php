<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PairController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Pair;

// ホーム
Route::get('/', [PostController::class, 'index'])->name('posts.index');

// 投稿詳細
Route::get('/posts/{post}', [PostController::class, 'detail'])->name('posts.detail');

// お気に入り
Route::post('/favorites/{post}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

// ログアウト
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// DM詳細ページ（チャット部屋）
Route::get('/dm/{dm}', [PairController::class, 'show'])->name('dm.show');

// メッセージ受信（Ajax）
Route::get('/dm/{dm}/message/reception', [PairController::class, 'fetch'])->name('dm.message.fetch');

// メッセージ送信（Ajax）
Route::post('/dm/{dm}/message/create', [DmController::class, 'send'])->name('dm.message.send');

Auth::routes();