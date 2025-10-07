<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileImageController extends Controller
{
    /**
     * プロフィール画像を更新
     */
    public function update(Request $request)
    {
        // バリデーション
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB以下
        ], [
            'image.required' => '画像を選択してください。',
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

        return redirect()->back()->with('success', '更新しました');
    }
}


