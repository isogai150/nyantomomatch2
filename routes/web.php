<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileImageController;
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
Route::post('/dm/{dm}/message/create', [PairController::class, 'send'])->name('dm.message.send');

// メッセージ編集（Ajax）
Route::put('/dm/message/{message}/update', [PairController::class, 'update'])->name('dm.message.update');

// メッセージ削除（Ajax）
Route::delete('/dm/message/{message}/delete', [PairController::class, 'destroy'])->name('dm.message.delete');

// DM一覧表示
Route::get('/dm', [PairController::class, 'index'])->name('dm.index');

// マイページ
Route::get('/mypage', [UserController::class, 'index'])->name('mypage.index');

// マイページ更新
Route::put('/mypage/edit/{user}', [UserController::class, 'edit'])->name('mypage.edit');

// ユーザーアイコン
Route::put('/profile/image', [UserController::class, 'updateImage'])->name('profile.image.update');

// ユーザー退会
Route::delete('/withdraw', [UserController::class, 'withdraw'])->name('user.withdraw');

// DM一覧表示
Route::get('/dm', [PairController::class, 'index'])->name('dm.index');

// DM作成
Route::post('/dm/create', [PairController::class, 'create'])->name('dm.create');

// DM削除
Route::delete('/dm/{dm}/delete', [PairController::class, 'delete'])->name('dm.delete');



//ユーザー認証系
Auth::routes();
