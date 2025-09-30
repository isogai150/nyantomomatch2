<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $favorites = [
        ['user_id' => 1, 'post_id' => 1],
        ['user_id' => 1, 'post_id' => 2],
        ['user_id' => 2, 'post_id' => 1],
        ['user_id' => 2, 'post_id' => 3],
        ['user_id' => 3, 'post_id' => 2],
        ['user_id' => 3, 'post_id' => 4],
        ['user_id' => 4, 'post_id' => 1],
        ['user_id' => 4, 'post_id' => 2],
        ['user_id' => 4, 'post_id' => 3],
        ];


        foreach ($favorites as $favorite) {
            $favorite['created_at'] = now();
            $favorite['updated_at'] = now();
            DB::table('favorites')->insert($favorite);
        }
    }
}