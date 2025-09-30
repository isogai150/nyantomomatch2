<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Post_videosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $videos = [

            ['post_id' => 3, 'video_path' => 'public/videos/seeder/854183-hd_1920_1080_25fps.mp4'],

            ['post_id' => 4, 'video_path' => 'public/videos/seeder/855282-hd_1280_720_25fps.mp4'],
        ];

        foreach ($videos as $video) {
            $video['created_at'] = now();
            $video['updated_at'] = now();
            DB::table('post_videos')->insert($video);
        }
    }
}