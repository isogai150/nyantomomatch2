<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\User\EditUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $user->name = $request->name;
        $user->email = $request->email;
        $user->description = $request->description;
        
        // パスワードが入力されている場合のみ更新
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        if ($request->filled('image_path')) {
            $user->image_path = $request->image_path;
        }
        
        $user->save();
        
        return redirect()->route('mypage.index', [
            'user' => $user
        ]);
    }
}
