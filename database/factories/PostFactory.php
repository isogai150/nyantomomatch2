<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;

/**
 * PostFactory
 * postsテーブル用のダミーデータ生成クラス。
 * NOT NULL制約のある全カラムをカバーしています。
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            // 投稿者（user_id）はテストで固定値または別途指定
            'user_id' => 1,

            // タイトル（20文字以内）
            'title' => $this->faker->words(2, true),

            // 性別 0=未入力, 1=オス, 2=メス
            'gender' => $this->faker->randomElement([0, 1, 2]),

            // 猫種
            'breed' => $this->faker->randomElement([
                'スコティッシュフォールド', 'マンチカン', 'アメリカンショートヘア', '雑種'
            ]),

            // 推定年齢（1〜15歳）
            'age' => $this->faker->numberBetween(1, 15),

            // 譲渡時の負担費用
            'cost' => $this->faker->numberBetween(500, 10000),

            // 現在の住居地
            'region' => $this->faker->randomElement([
                '東京都', '神奈川県', '大阪府', '福岡県', '北海道'
            ]),

            // 予防接種関係
            'vaccination' => $this->faker->randomElement([
                '済み', '未接種', '不明'
            ]),

            // 病歴
            'medical_history' => $this->faker->randomElement([
                '特になし', '過去に風邪あり', 'ワクチンアレルギー'
            ]),

            // 詳細説明
            'description' => $this->faker->sentence(10),

            // 掲載開始日（今日）
            'start_date' => now(),

            // 掲載終了日はnull可
            'end_date' => null,

            // ステータス 0=募集中 / 1=トライアル中 / 2=譲渡済
            'status' => 0,
        ];
    }
}
