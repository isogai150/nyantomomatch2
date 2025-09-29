<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 一般ユーザー
        $users = ['user1', 'user2'];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'name' => $user,
                'email' => $user.'@email.com',
                'password' => Hash::make('password'),
                'role' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 投稿権限ユーザー
        $authorizedUsers = ['user3', 'user4'];

        foreach ($authorizedUsers as $authorizedUser) {
            DB::table('users')->insert([
                'name' => $authorizedUser,
                'email' => $authorizedUser.'@email.com',
                'password' => Hash::make('password'),
                'role' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
