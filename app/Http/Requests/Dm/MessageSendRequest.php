<?php

namespace App\Http\Requests\Dm;

use Illuminate\Foundation\Http\FormRequest;

class MessageSendRequest extends FormRequest
{
    /**
     * 認可（誰が送信できるか）
     */
    public function authorize(): bool
    {
        // ログインユーザーのみメッセージ送信可能
        return auth()->check();
    }

    /**
     * バリデーションルール
     */
    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:1000'],
        ];
    }

    /**
     * カスタムエラーメッセージ
     */
    public function messages(): array
    {
        return [
            'message.required' => 'メッセージを入力してください。',
            'message.string'   => 'メッセージは文字列で入力してください。',
            'message.max'      => 'メッセージは1000文字以内で入力してください。',
        ];
    }
}
