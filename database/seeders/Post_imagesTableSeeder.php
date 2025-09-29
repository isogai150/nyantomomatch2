<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images = [
            ['post_id' => 1, 'image_path' => 'public/images/seeder/orange-tabby-cat.png'],
            ['post_id' => 1, 'image_path' => 'public/images/seeder/orange-cat-sleeping.jpg'],
            ['post_id' => 1, 'image_path' => 'public/images/seeder/orange-cat-playing.jpg'],

            ['post_id' => 3, 'image_path' => 'public/images/seeder/black-cat-portrait.png'],
        ];

        foreach ($images as $image) {
            $image['created_at'] = now();
            $image['updated_at'] = now();
            DB::table('post_images')->insert($image);
        }
    }
}
