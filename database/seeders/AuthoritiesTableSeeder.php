<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Authority;

class AuthoritiesTableSeeder extends Seeder
{
    public function run()
    {
        $params = [
            [
                'user_id' => 3,
                'reason' => '猫を保護したのですが、自宅で飼えないので里親募集に出したいです。',
                'status' => 1,
            ],
            [
                'user_id' => 4,
                'reason' => '2025/09/29(月)の13:35頃に家の近くの神社で子猫を発見し保護しました、かなり衰弱していたので動物病院に連れて行って処置をしてもらいました。現在自宅で保護しているのですが、長期的に飼育はできないので里親募集をしたいので申請いたしました。よろしくお願いします。',
                'status' => 1,
            ],
            [
                'user_id' => 1,
                'reason' => '',
                'status' => 0,
            ],
            [
                'user_id' => 2,
                'reason' => '123456789',
                'status' => 2,
            ],
        ];

        foreach ($params as $param) {
            Authority::firstOrCreate(
                // 重複チェック条件
                ['user_id' => $param['user_id']],
                [
                    'reason'     => $param['reason'],
                    'status'     => $param['status'],
                ]
            );
        }
    }
}
