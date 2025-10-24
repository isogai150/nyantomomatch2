<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        $user_id = auth()->id();

        return [
            'name' => 'required|max:20',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user_id)
            ],
            'password' => 'nullable|string|min:8|max:20|alpha_num|confirmed',
            // 'image_path' => 'nullable|string|min:8|max:20|alpha_num',
            'description' => 'nullable|string|max:500',
            // 'reason' => 'required|string|max:500',
            // 'agree' => 'required|accepted',
        ];
    }

    public function attributes()
    {
        //左：Viewのname属性
        //右：表示したいテキスト
        return [
            'name' => '氏名',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
            // 'image_path' => 'プロフィール画像',
            'description' => '自己紹介文',
            'reason' => '申請理由',
            'agree' => '「利用規約に同意する」',
        ];
    }
}