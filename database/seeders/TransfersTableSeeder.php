<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransfersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transfers = [
            [
                'userA_id' => 3,
                'userB_id' => 1,
                'post_id' => 1,
                'confirmed_at' => now()->subDays(5),
            ],
            [
                'userA_id' => 4,
                'userB_id' => 2,
                'post_id' => 4,
                'confirmed_at' => now()->subDays(2),
            ],
            [
                'userA_id' => 3,
                'userB_id' => 2,
                'post_id' => 2,
                'confirmed_at' => now()->subDays(1),
            ],
        ];

        foreach ($transfers as $transfer) {
            $transfer['created_at'] = now();
            $transfer['updated_at'] = now();
            DB::table('transfers')->insert($transfer);
        }
    }
}
