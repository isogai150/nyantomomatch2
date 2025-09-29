<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class PostsTableSeeder extends Seeder
{
    public function run()
    {
        $params = [
            [
                'user_id' => 3,
                'title' => '子猫保護しました！里親募集しています！',
                'gender' => 0,
                'age' => 1,
                'cost' => 30000,
                'region' => '東京都',
                'vaccination' => 'テキストテキストテキストテキストテキストテキスト',
                'medical_history' => 'なし',
                'description' => str_repeat('description', 10),
                'start_date' => now(),
                'status' => 0
            ],
            [
                'user_id' => 3,
                'title' => 'タイトル',
                'gender' => 11,
                'age' => 4,
                'cost' => 0,
                'region' => '東京都',
                'vaccination' => str_repeat('1', 150),
                'medical_history' => '不明',
                'description' => '',
                'start_date' => now(),
                'end_date' => now()->addDays(10),
                'status' => 1
            ],
            [
                'user_id' => 4,
                'title' => '',
                'gender' => 2,
                'age' => 3,
                'cost' => 123456789,
                'region' => '東京都',
                'vaccination' => ' ',
                'medical_history' => 'なし',
                'description' => ' ',
                'start_date' => now(),
                'status' => 0
            ],
            [
                'user_id' => 4,
                'title' => '里親募集中',
                'gender' => 2,
                'age' => 10,
                'cost' => 10000,
                'region' => '東京都',
                'vaccination' => 'テキストテキストテキストテキストテキストテキスト',
                'medical_history' => 'なし',
                'description' => str_repeat('description', 10),
                'start_date' => now(),
                'status' => 2
            ],
        ];

        foreach ($params as $param) {
            Post::create($param);
        }
    }
}
