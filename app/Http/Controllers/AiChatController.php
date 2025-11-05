<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Facades\Gemini; // パッケージのFacade

class AiChatController extends Controller
{
    public function ask(Request $request)
    {
        $userQuestion = $request->input('question');

        // モデル指定（無料枠多め）
        $result = Gemini::generateContent($userQuestion, [
            'model' => 'gemini-2.0-flash', 
        ]);

        $aiAnswer = $result->text;

        // JSON形式で返す（Ajaxなどに対応）
        return response()->json(['answer' => $aiAnswer]);
    }
}
