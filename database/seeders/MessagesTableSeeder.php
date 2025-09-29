<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = DB::table('users')->pluck('id')->values();
        $pairs = DB::table('pairs')->pluck('id')->values();

        $params = [
            [
                'user_id' => $users[0],
                'pair_id' => $pairs[0],
                'content' => '始めまして！猫の投稿に惹かれて連絡しました！よろしくお願いします！！！',
            ],
            [
                'user_id' => $users[2],
                'pair_id' => $pairs[0],
                'content' => '連絡ありがとうございます。里親希望の方でよろしいでしょうか？',
            ],
            [
                'user_id' => $users[0],
                'pair_id' => $pairs[0],
                'content' => 'はい！里親希望です！！！',
            ],
            [
                'user_id' => $users[0],
                'pair_id' => $pairs[1],
                'content' => 'Hello!',
            ],
            [
                'user_id' => $users[3],
                'pair_id' => $pairs[1],
                'content' => '日本語でやり取り可能でしょうか？',
            ],
            [
                'user_id' => $users[0],
                'pair_id' => $pairs[1],
                'content' => 'No!!!',
            ],
            [
                'user_id' => $users[1],
                'pair_id' => $pairs[2],
                'content' => '                                                                        ',
            ],
            [
                'user_id' => $users[2],
                'pair_id' => $pairs[2],
                'content' => 'いたずらやめてください',
            ],
            [
                'user_id' => $users[1],
                'pair_id' => $pairs[2],
                'content' => '124q345654756587979087',
            ],
            [
                'user_id' => $users[2],
                'pair_id' => $pairs[2],
                'content' => '通報しました',
            ],
            [
                'user_id' => $users[1],
                'pair_id' => $pairs[3],
                'content' => '猫ください',
            ],
            [
                'user_id' => $users[3],
                'pair_id' => $pairs[3],
                'content' => 'いきなりですね',
            ],
            [
                'user_id' => $users[1],
                'pair_id' => $pairs[3],
                'content' => 'あああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ',
            ],
        ];

        foreach ($params as $param) {
            $param['created_at'] = now();
            $param['updated_at'] = now();
        }
        DB::table('messages')->insert($param);
    }
}
