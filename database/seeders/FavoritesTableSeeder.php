<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reports = [
            [
                'user_id' => 3,
                'pair_id' => 3,
                'message_id' => 7,
                'status' => 0,
            ],

            [
                'user_id' => 4,
                'pair_id' => 3,
                'message_id' => 9,
                'status' => 1,
            ],

            [
                'user_id' => 3,
                'pair_id' => 4,
                'message_id' => 13,
                'status' => 2,
            ],
        ];

        foreach ($reports as $report) {
            $report['created_at'] = now();
            $report['updated_at'] = now();
            DB::table('message_reports')->insert($report);
        }
    }
}
