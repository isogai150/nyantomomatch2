<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Authority;
use App\Http\Requests\User\EditUser;
use App\Http\Requests\User\AuthorityRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // マイページ表示
    public function index()
    {
        $user = Auth::user();
        // dd($user);
        return view('mypage/index', [
            'user' => $user,
        ]);
    }

    // マイページ更新
    public function edit(User $user, EditUser $request)
    {

        $user = Auth::user();

        // 更新データを準備
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'description' => $request->description,
        ];

        // パスワードが入力されている場合のみ更新
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('mypage.index', [
            'user' => $user
        ]);
    }

    // プロフィール画像を更新
    public function updateImage(Request $request)
    {
        // バリデーション
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB以下
        ], [
            'image.image' => '有効な画像ファイルを選択してください。',
            'image.mimes' => 'JPEG、PNG、JPG、GIF形式の画像のみアップロード可能です。',
            'image.max' => '画像サイズは2MB以下にしてください。',
        ]);

        $user = Auth::user();

        // 古い画像を削除
        if ($user->image_path) {
            Storage::disk('public')->delete('profile_images/' . $user->image_path);
        }

        // 新しい画像を保存
        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

        // storage/app/public/profile_images に保存
        $image->storeAs('profile_images', $imageName, 'public');

        // データベースを更新
        $user->image_path = $imageName;
        $user->save();

        return redirect()->route('mypage.index')->with('success', 'プロフィール画像を更新しました。');
    }

    // ユーザー退会処理
    public function withdraw(Request $request)
    {
        $user = Auth::user();

        // 論理削除（deleted_atに現在時刻をセット）
        $user->delete();

        // ログアウト処理
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // トップページに遷移
        return redirect('/')->with('status', '退会処理が完了しました。');
    }

    // 投稿権限申請処理
    public function requestPostPermission(AuthorityRequest $request)
    {
        $user = Auth::user();

        // すでに申請中または承認済みの申請がないかチェック
        $existingRequest = Authority::where('user_id', $user->id)
            ->whereIn('status', [Authority::STATUS_PENDING, Authority::STATUS_APPROVED])
            ->first();

        if ($existingRequest) {
            if ($existingRequest->status === Authority::STATUS_APPROVED) {
                return redirect()->route('mypage.index')
                    ->with('error', 'すでに投稿権限が承認されています。');
            }

            return redirect()->route('mypage.index')
                ->with('error', '申請が審査中です。結果をお待ちください。');
        }

        // 新規申請を作成
        Authority::create([
            'user_id' => $user->id,
            'reason' => $request->reason,
            'status' => Authority::STATUS_PENDING,
        ]);

        return redirect()->route('mypage.index')
            ->with('success', '投稿権限の申請を送信しました。審査結果をお待ちください。');
    }
}