<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
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
                'description' => 'descriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescription',
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
                'vaccination' => '11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
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
                'description' => 'descriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescriptiondescription',
                'start_date' => now(),
                'status' => 2
            ],
        ];

        foreach ($params as $param) {
            $param['created_at'] = now();
            $param['updated_at'] = now();
        }
        DB::table('posts')->insert($param);
    }
}
