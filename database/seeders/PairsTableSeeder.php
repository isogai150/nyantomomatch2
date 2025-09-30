<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pair;
use App\Models\User;

class PairsTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::pluck('id')->toArray();

        $params = [
            ['userA_id' => $users[0], 'userB_id' => $users[2]],
            ['userA_id' => $users[0], 'userB_id' => $users[3]],
            ['userA_id' => $users[1], 'userB_id' => $users[2]],
            ['userA_id' => $users[1], 'userB_id' => $users[3]],
        ];

        foreach ($params as $param) {
            Pair::firstOrCreate(
                ['userA_id' => $param['userA_id'], 'userB_id' => $param['userB_id']]
            );
        }
    }
}
