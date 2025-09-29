<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostReportsTableSeeder extends Seeder
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
                'user_id' => 2,
                'post_id' => 1,
                'status' => 0,
            ],

            [
                'user_id' => 1,
                'post_id' => 2,
                'status' => 1,
            ],

            [
                'user_id' => 2,
                'post_id' => 3,
                'status' => 2,
            ],

            [
                'user_id' => 1,
                'post_id' => 4,
                'status' => 0,
            ],
        ];

        foreach ($reports as $report) {
            $report['created_at'] = now();
            $report['updated_at'] = now();
            DB::table('post_reports')->insert($report);
        }
    }
}
