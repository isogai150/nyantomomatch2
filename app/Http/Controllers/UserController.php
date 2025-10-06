<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\User\EditUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('mypage/index', [
            'user' => $user,
        ]);
    }

    public function edit(User $user, EditUser $request)
    {
        // 編集内容のユーザー情報を取得
        $this->checkRelation($user);

        //usersテーブルの列に入力内容をセット
        //左：usersテーブルの列
        //右：入力内容
        $user->title = $request->name;
        $user->status = $request->email;
        $user->due_date = $request->description;

        //saveメソッドで、データを保存してる
        $task->save();

        return redirect()->route('mypage.index', [
            'user' => $user,
        ]);
    }
}
