<?php

namespace App\Http\Requests\Dm;

use Illuminate\Foundation\Http\FormRequest;

class DmSearchRequest extends FormRequest
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
            'search' => 'nullable|string|max:50',
        ];
    }

    public function messages()
    {
        return [
            'search.max' => '検索キーワードは50文字以内で入力してください。',
        ];
    }

    /**
     * バリデーション失敗時の属性名
     */
    public function attributes()
    {
        return [
            'search' => '検索キーワード',
        ];
    }
}
