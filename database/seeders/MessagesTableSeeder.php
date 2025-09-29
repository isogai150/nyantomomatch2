<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;
use App\Models\Pair;

class MessagesTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::pluck('id')->toArray();
        $pairs = Pair::pluck('id')->toArray();

        $params = [
            ['user_id' => $users[0], 'pair_id' => $pairs[0], 'content' => '始めまして！猫の投稿に惹かれて連絡しました！よろしくお願いします！！！'],
            ['user_id' => $users[2], 'pair_id' => $pairs[0], 'content' => '連絡ありがとうございます。里親希望の方でよろしいでしょうか？'],
            ['user_id' => $users[0], 'pair_id' => $pairs[0], 'content' => 'はい！里親希望です！！！'],
            ['user_id' => $users[0], 'pair_id' => $pairs[1], 'content' => 'Hello!'],
            ['user_id' => $users[3], 'pair_id' => $pairs[1], 'content' => '日本語でやり取り可能でしょうか？'],
            ['user_id' => $users[0], 'pair_id' => $pairs[1], 'content' => 'No!!!'],
            ['user_id' => $users[1], 'pair_id' => $pairs[2], 'content' => '                                                                        '],
            ['user_id' => $users[2], 'pair_id' => $pairs[2], 'content' => 'いたずらやめてください'],
            ['user_id' => $users[1], 'pair_id' => $pairs[2], 'content' => '124q345654756587979087'],
            ['user_id' => $users[2], 'pair_id' => $pairs[2], 'content' => '通報しました'],
            ['user_id' => $users[1], 'pair_id' => $pairs[3], 'content' => '猫ください'],
            ['user_id' => $users[3], 'pair_id' => $pairs[3], 'content' => 'いきなりですね'],
            ['user_id' => $users[1], 'pair_id' => $pairs[3], 'content' => str_repeat('あ', 100)],
        ];

        foreach ($params as $param) {
            Message::create($param);
        }
    }
}
