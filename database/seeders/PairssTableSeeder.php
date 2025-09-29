<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PairssTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = DB::table('users')->pluck('id')->values();

        $params = [
            [
                'userA_id' => $users[0],
                'userB_id' => $users[2],
            ],
            [
                'userA_id' => $users[0],
                'userB_id' => $users[3],
            ],
            [
                'userA_id' => $users[1],
                'userB_id' => $users[2],
            ],
            [
                'userA_id' => $users[1],
                'userB_id' => $users[3],
            ],
        ];

        foreach ($params as $param) {
            $param['created_at'] = now();
            $param['updated_at'] = now();
        }
        DB::table('pairs')->insert($param);
    }
}
