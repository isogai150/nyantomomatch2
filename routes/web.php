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

// 投稿一覧
Route::get('/catpost', [PostController::class, 'index'])->name('catpost.index');


// ===========================================================================================

// 自分の投稿一覧表示機能
Route::get('/my/catpost', [PostController::class, 'myCatpost'])->name('mycatpost.index');

// 編集画面表示
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');

// 編集内容の更新
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');

// 編集内容の削除
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

// ===========================================================================================


// お気に入りトグル
Route::post('/favorites/{post}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

// お気に入り一覧表示
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');

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

// 権限の申請
Route::post('mypage/request-post-permission', [Controller::class, ''])->name('request.post.permission');



//ユーザー認証系
Auth::routes();
