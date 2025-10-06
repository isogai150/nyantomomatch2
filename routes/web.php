<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

// ホーム
Route::get('/', [PostController::class, 'index'])->name('posts.index');

// 投稿詳細
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// お気に入り
Route::post('/favorites/{post}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

// マイページ
Route::get('/mypage', [UserController::class, 'index'])->name('mypage.index');

Route::post('/mypage/{user}/edit', [UserController::class, 'edit']);


Auth::routes();