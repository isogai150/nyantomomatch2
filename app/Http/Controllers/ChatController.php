<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index');
    }


    public function ask(Request $request)
    {
        // バリデーション：質問が空でないこと、文字列であること、最大1000文字まで
        $request->validate([
            'question' => 'required|string|max:1000',
        ]);

        // ユーザーから送られた質問を取得
        $question = $request->input('question');

        // .envファイルに保存されているAPIキーを取得
        $apiKey = config('services.gemini.api_key');

        // APIキーが設定されていない場合はエラーを返す
        if (empty($apiKey)) {
            Log::error('GEMINI_API_KEY が設定されていません。');
            return response()->json([
                'answer' => 'サーバー設定エラー：APIキーがありません。'
            ], 500);
        }

        // 使用するGeminiモデルの指定
        $model = 'gemini-2.5-flash';

        // Google Generative Language APIのエンドポイント
        // APIキーをURLパラメータとして渡す
        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        try {
            // HTTPリクエストを送信
            // withHeaders()でContent-Typeを指定し、POSTで質問内容を送信する
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($endpoint, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $question] // ユーザーの質問をGeminiに渡す
                        ]
                    ]
                ]
            ]);

            // レスポンス内容をログに記録（デバッグ用）
            Log::info('Gemini API レスポンス', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            // もしAPIが失敗を返した場合の処理
            if ($response->failed()) {
                Log::error('Gemini API 呼び出し失敗', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
                return response()->json([
                    'answer' => 'AIサービスへの接続に失敗しました。もう一度お試しください。',
                ], 500);
            }

            // APIからのレスポンス（JSON）を取得
            $payload = $response->json();

            // AIの回答部分を取り出す
            // Geminiのレスポンス構造：
            // candidates[0].content.parts[0].text に回答が入っている
            $answer = $payload['candidates'][0]['content']['parts'][0]['text']
                ?? 'AIからの回答を取得できませんでした。';

            // AIの回答をJSON形式で返す
            return response()->json([
                'answer' => $answer,
            ]);

        } catch (\Exception $e) {
            // 通信やAPI処理中に例外が発生した場合のエラーハンドリング
            Log::error('Gemini API 通信例外', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'answer' => '内部エラーが発生しました。ログを確認してください。',
            ], 500);
        }
    }
}
