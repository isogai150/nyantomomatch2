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
        // ★★★ 新規作成か編集かを判定 ★★★
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        $rules = [
            'title' => 'required|string|max:20',
            'age' => 'required|numeric|min:0|max:30',
            'gender' => 'required|integer',
            'breed' => 'required|string|max:50',
            'region' => 'required|string|max:100',
            'start_date' => 'required|date',

            // 掲載終了日は必須ではないので「nullable」
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|integer',

            'vaccination' => 'required|string|max:500',
            'medical_history' => 'required|string|max:500',

            'description' => 'required|string|max:1000',

            'cost' => 'required|numeric|min:0|max:1000000',
        ];

        // ★★★ 画像のバリデーション（編集時は任意） ★★★
        if ($isUpdate) {
            // 編集時：画像は任意、動画も任意
            $rules['images'] = 'nullable|array|max:3';
            $rules['images.*'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
            $rules['video'] = 'nullable|file|mimes:mp4,mov,avi,wmv|max:10240';
        } else {
            // 新規作成時：画像は必須
            $rules['image'] = 'required|array|max:3';
            $rules['image.*'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
            $rules['video'] = 'nullable|file|mimes:mp4,mov,avi,wmv|max:10240';
        }

        return $rules;
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
            'end_date.after_or_equal' => '掲載終了日は開始日以降を指定してください。',

            // 新規作成用（image）
            'image.required' => '画像ファイルを選択してください。',
            'image.array' => '画像の形式が正しくありません。',
            'image.max' => '画像は最大3枚までアップロードできます。',
            'image.*.image' => '画像ファイルを選択してください。',
            'image.*.mimes' => '画像は jpeg, png, jpg, gif のいずれかでアップロードしてください。',
            'image.*.max' => '画像は2MB以内でアップロードしてください。',

            // 編集用（images）
            'images.array' => '画像の形式が正しくありません。',
            'images.max' => '画像は最大3枚までアップロードできます。',
            'images.*.image' => '画像ファイルを選択してください。',
            'images.*.mimes' => '画像は jpeg, png, jpg, gif のいずれかでアップロードしてください。',
            'images.*.max' => '画像は2MB以内でアップロードしてください。',

            'video.file' => '動画ファイルを選択してください。',
            'video.mimes' => '動画は mp4, mov, avi, wmv のいずれかでアップロードしてください。',
            'video.max' => '動画は10MB以内でアップロードしてください。',

            'vaccination.required' => '予防接種の情報を入力してください。',
            'vaccination.max' => '予防接種の情報は500文字以内で入力してください。',
            'medical_history.required' => '病歴の情報を入力してください。',
            'medical_history.max' => '病歴の情報は500文字以内で入力してください。',

            'description.required' => '詳細説明を入力してください。',
            'description.max' => '詳細説明は1000文字以内で入力してください。',

            'cost.required' => '費用を入力してください。',
            'cost.numeric' => '費用は数値で入力してください。',
            'cost.min' => '数値で0以上で入力してください。',
            'cost.max' => '数値で1000000以内で入力してください。',
        ];
    }
}