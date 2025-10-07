<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;

// ホーム
Route::get('/', [PostController::class, 'index'])->name('posts.index');

// 投稿詳細
Route::get('/posts/{post}', [PostController::class, 'detail'])->name('posts.detail');

// 投稿一覧
Route::get('/catpost', [PostController::class, 'index'])->name('catpost.index');

// お気に入り
Route::post('/favorites/{post}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

// ログアウト
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



Auth::routes();