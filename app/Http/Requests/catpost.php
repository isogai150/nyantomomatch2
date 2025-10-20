<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CatPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // true にしないとバリデーションが実行されない
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
            'title' => 'required|string|max:20',
            'age' => 'required|numeric|min:0|max:30',
            'gender' => 'required',
            'breed' => 'required|string|max:50',
            'region' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            // 画像関連
            'image' => 'required|array|max:4',          // 配列として必須・最大4枚
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

            // 動画関連
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:10240', // 最大10MB

            'vaccination' => 'required|string|max:500',
            'medical_history' => 'required|string|max:500',

            'description' => 'required|string|max:1000',

            'cost' => 'required|numeric|min:0|max:1000000',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'タイトルを入力してください。',
            'title.max' => 'タイトルは20文字以内で入力してください。',
            'age.required' => '推定年齢を入力してください。',
            'age.numeric' => '年齢は数値で入力してください。',
            'gender.required' => '性別を選択してください。',
            'breed.required' => '猫の品種を入力してください。',
            'region.required' => '所在地を入力してください。',
            'start_date.required' => '掲載開始日を入力してください。',
            'end_date.required' => '掲載終了日を入力してください。',
            'end_date.after_or_equal' => '掲載終了日は開始日以降を指定してください。',

            'image.required' => '画像ファイルを選択してください。',
            'image.array' => '画像の形式が正しくありません。',
            'image.max' => '画像は最大3枚までアップロードできます。',
            'image.*.image' => '画像ファイルを選択してください。',
            'image.*.mimes' => '画像は jpeg, png, jpg, gif のいずれかでアップロードしてください。',
            'image.*.max' => '画像は2MB以内でアップロードしてください。',

            'video.file' => '動画ファイルを選択してください。',
            'video.mimes' => '動画は mp4, mov, avi, wmv のいずれかでアップロードしてください。',
            'video.max' => '動画は10MB以内でアップロードしてください。',

            'vaccination' => '予防接種の情報を入力してください。',
            'vaccination.max' => '予防接種の情報は500文字以内で入力してください。',
            'medical_history' => '病歴の情報を入力してください。',
            'medical_history.max' => '病歴の情報は500文字以内で入力してください。',

            'cost.required' => '費用を入力してください。',
            'cost.numeric' => '費用は数値で入力してください。',
            'cost.min' => '数値で0以上で入力してください。',
            'cost.max' => '数値で1000000以内で入力してください。',
        ];
    }

}
