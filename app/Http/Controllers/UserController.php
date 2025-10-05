<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(User $user)
    {
        $user = Auth::user()->users()->get();

        return view('mypage/index', [
            'user' => $user,
        ]);
    }
}
