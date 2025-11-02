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
use App\Http\Controllers\PaymentController;
use App\Http\Requests\CatPost;
use App\Http\Controllers\AdministratorController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\TransferController;
use App\Http\Middleware\Firewall;
use App\Models\Authority;
use App\Http\Controllers\PostReportController;
use App\Http\Controllers\DompdfController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\MessageReportController;
use App\Http\Controllers\AiChatController;

Route::get('/chat', function () {
    return view('chat.index');
});

Route::post('/ask-gemini', [AiChatController::class, 'ask'])->name('ask.gemini');


// ホーム
Route::get('/', [PostController::class, 'index'])->name('posts.index');

Route::middleware('auth')->group(function () {

  // 投稿詳細
  Route::get('/posts/{post}', [PostController::class, 'detail'])->name('posts.detail');

  // 投稿一覧
  Route::get('/catpost', [PostController::class, 'index'])->name('catpost.index');

  // 自分の投稿一覧表示機能
  Route::get('/my/catpost', [PostController::class, 'myCatpost'])->name('mycatpost.index');

  // 編集画面表示
  Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');

  // 編集内容の更新
  Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');

  // 編集内容の削除
  Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

  // 猫の情報投稿作成画面
  Route::get('/catpost/create', [PostController::class, 'create'])->name('posts.create');

  // 猫の情報投稿作成画面：バリデーションメッセージ
  Route::post('/catpost/store', [PostController::class, 'store'])->name('catpost.store');

  // 投稿編集画面
  Route::get('/my/catpost/{post}/edit', [PostController::class, 'edit'])->name('catpost.edit');

  // 投稿更新処理
  Route::put('/my/catpost/{post}', [PostController::class, 'update'])->name('catpost.update');

  // 画像・動画削除処理
  Route::delete('/catpost/media/{type}/{id}', [PostController::class, 'deleteMedia'])->name('media.delete');

  // 投稿削除処理
  Route::delete('/my/catpost/{post}/delete', [PostController::class, 'destroy'])->name('catpost.destroy');

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
  Route::post('mypage/request-post-permission', [UserController::class, 'requestPostPermission'])->name('request.post.permission');

  // 決済完了ページ
  Route::get('/checkout/success', [PaymentController::class, 'success'])->name('payment.success');

  // キャンセルページ表示
  Route::get('/checkout/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

  // カート情報ページ表示
  Route::get('/checkout/{post}', [PaymentController::class, 'showcart'])->name('payment.cart');

  // 決済情報入力ページ表示
  Route::get('/checkout/{post}/payment', [PaymentController::class, 'showForm'])->name('payment.form');

  // 契約書提出
  Route::post('/dm/{dm}/transfer/submit', [TransferController::class, 'submit'])->name('transfer.submit');

  // 譲渡資料送信（投稿者用）
  Route::post('/dm/{dm}/transfer/send', [TransferController::class, 'send'])->name('transfer.send');

  // 譲渡資料確認（里親希望者用）
  Route::get('/dm/{dm}/document', [TransferController::class, 'showDocument'])->name('document.show');

  // 双方の合意（両者押下で成立）
  Route::post('/dm/{dm}/transfer/agree', [TransferController::class, 'agree'])->name('transfer.agree');

  // 投稿通報
  Route::post('/report/post/{post}', [PostReportController::class, 'store'])->name('report.post');

  // 譲渡契約書PDF
  Route::get('/transfer/{pair}/contract/pdf', [DompdfController::class, 'downloadContract'])->name('transfer.contract.pdf');

  // ブロック登録
  Route::post('/block/{userId}', [BlockController::class, 'store'])->name('block.store');

  // ブロック解除
  Route::delete('/block/{userId}', [BlockController::class, 'destroy'])->name('block.destroy');

  // メッセージ通報機能
  Route::post('/dm/{dm}/message/{message}/report', [MessageReportController::class, 'store'])->name('report.message');
});

// 管理者ログイン関連
Route::prefix('admin')->name('admin.')->middleware('firewall')->group(function () {

  Route::get('login', [AdministratorController::class, 'showLoginForm'])->name('login');

  Route::post('login', [AdministratorController::class, 'login']);

  Route::get('logout', [AdministratorController::class, 'logout'])->name('logout');

  // ログイン後
  Route::middleware('auth:admin')->group(function () {

    // ダッシュボード
    Route::get('dashboard', [AdministratorController::class, 'index'])->name('dashboard');

    // 投稿権限申請一覧表示
    Route::get('authority', [AdministratorController::class, 'authorityList'])->name('authority');

    // 投稿権限申請キャンセル処理
    Route::delete('authority/{authority}/cancel', [AdministratorController::class, 'authorityCancel'])->name('authority.cancel');

    // 投稿権限申請承認処理
    Route::put('authority/{authority}/approval', [AdministratorController::class, 'AuthorityApproval'])->name('authority.approval');

    // 投稿権限申請詳細表示
    Route::get('authority/{authority}', [AdministratorController::class, 'authorityDetail'])->name('authority.detail');

    // DM一覧表示
    Route::get('dm', [AdministratorController::class, 'dmList'])->name('dm');

    // DM詳細表示
    Route::get('dm/{dm}', [AdministratorController::class, 'detail'])->name('dm.detail');

    //投稿通報一覧表示
    Route::get('post-reports', [AdministratorController::class, 'postReports'])->name('post.reports');

    // 投稿通報詳細
    Route::get('post-reports/{report}', [AdministratorController::class, 'postReportDetail'])->name('post.report.detail');

    // 投稿削除（管理者用）
Route::delete('post/{post}/delete', [AdministratorController::class, 'postDestroy'])->name('post.delete');


    // 通報ステータス更新（対応済）
    Route::put('post-reports/{report}/resolve', [AdministratorController::class, 'postReportResolve'])->name('post.report.resolve');

    // 通報ステータス更新（却下）
    Route::put('post-reports/{report}/reject', [AdministratorController::class, 'postReportReject'])->name('post.report.reject');

    // メッセージ削除（管理者用）
    Route::delete('dm/{dm}/message/{message}/delete', [AdministratorController::class, 'messageDestroy'])->name('dm.message.delete');

    // DM通報一覧表示
    Route::get('report/dm', [AdministratorController::class, 'dmReportList'])->name('report');

    // DM通報解決済み処理
    Route::post('report/dm/{id}/resolve', [AdministratorController::class, 'dmReportResolve'])->name('report.resolve');

    // DM通報却下処理
    Route::post('report/dm/{id}/reject', [AdministratorController::class, 'dmReportReject'])->name('report.reject');

    // DM通報詳細表示
    Route::get('report/dm/{id}', [AdministratorController::class, 'dmReportDetail'])->name('report.detail');

    // ユーザー一覧表示
    Route::get('users', [AdministratorController::class, 'userList'])->name('users');

    // ユーザー詳細
    Route::get('users/{id}', [AdministratorController::class, 'userDetail'])->name('user.detail');

    // ユーザーBAN
    Route::post('users/{id}/ban', [AdministratorController::class, 'userBan'])->name('user.ban');

    // ユーザーBAN解除
    Route::post('users/{id}/unban', [AdministratorController::class, 'userUnban'])->name('user.unban');

    // 譲渡成立一覧
    Route::get('transfer', [AdministratorController::class, 'transferList'])->name('transfer');

    //管理者退会
    Route::delete('account/delete', [AdministratorController::class, 'destroy'])->name('user.delete');
  });
});





// =====================================デバック用============================================
// エラーハンドリング（デバック用）
// Route::get('/test403', function () {
//     abort(403);
// })->name('test.403');

// Route::get('/test404', function () {
//     abort(404);
// })->name('test.404');

// Route::get('/test419', function () {
//     abort(419);
// })->name('test.419');

// Route::get('/test500', function () {
//     abort(500);
// })->name('test.500');

// Route::get('/test503', function () {
//     abort(503);
// })->name('test.503');

// Firewall（IPアドレスデバック用）
// Route::get('/debug/ip', function (\Illuminate\Http\Request $request) {
//     return response()->json([
//         'client_ip' => $request->ip(),
//         'getClientIps' => $request->getClientIps(),
//         'allowed_ips' => env('ALLOWED_ADMIN_IPS'),
//         'app_env' => env('APP_ENV'),
//     ]);
// });


//ユーザー認証系
Auth::routes();