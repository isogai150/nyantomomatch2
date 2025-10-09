<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileImageController;
use App\Http\Controllers\PairController;
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



// マイページ
Route::get('/mypage', [UserController::class, 'index'])->name('mypage.index');
Route::put('/mypage/{user}/edit', [UserController::class, 'edit'])->name('mypage.edit');

// ユーザーアイコン
Route::put('/profile/image', [UserController::class, 'updateImage'])->name('profile.image.update');

// ユーザー退会
Route::delete('/withdraw', [UserController::class, 'withdraw'])->name('user.withdraw');

// DM一覧表示
Route::get('/dm', [PairController::class, 'index'])->name('dm.index');
// Route::get('/dm/{pairId}', [PairController::class, 'show'])->name('dm.show');

Auth::routes();