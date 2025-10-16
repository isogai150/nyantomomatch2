<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AuthorityRequest extends FormRequest
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
        return [
            'reason' => 'required|string|max:500',
            'agree' => 'required|accepted',
        ];
    }

    public function messages()
    {
        return [
            'reason.required' => '申請理由を入力してください。',
            'reason.max' => '申請理由は500文字以内で入力してください。',
            'agree.required' => '利用規約への同意が必要です。',
            'agree.accepted' => '利用規約に同意してください。',
        ];
    }
}
