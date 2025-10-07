<?php

namespace App\Http\Controllers;

use App\Models\Pair;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class PairController extends Controller
{
    // DMの詳細表示（チャット画面）の表示
    public function detail ($dm)
    {
        $partner = Uere::findOrFail($dm);
    }
}
