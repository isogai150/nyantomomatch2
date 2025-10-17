<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CatPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
            'title' => 'required|string|max:50',
            'age' => 'required|numeric|min:0|max:30',
            'gender' => 'required|in:オス,メス',
            'kinds' => 'required|string|max:50',
            'location' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vaccine' => 'nullable|string|max:500',
            'disease' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0|max:1000000',
        ];
    }

    public function validation()
    {
            return [
            'title.required' => 'タイトルは50文字以内で入力してください。',
            'age.required' => '推定年齢を入力してください。',
            'gender.required' => '性別を選択してください。',
            'kinds.required' => '猫の品種を入力してください。',
            'location.required' => '所在地を入力してください。',
            'start_date.required' => '掲載開始日を入力してください。',
            'end_date.required' => '掲載終了日を入力してください。',
            // 'end_date.after_or_equal' => '掲載終了日は開始日以降の日付を指定してください。',
            'photo.required' => '画像を最低1枚アップロードしてください。',
            'vaccine.required' => '予防接種の情報を入力してください。',
            'disease.required' => '病歴の情報を入力してください。',
            'price.required' => '費用を入力してください。',
                    ];
    }

}
