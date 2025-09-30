<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
            User::firstOrCreate(
                ['email' => $user.'@email.com'],
                [
                    'name' => $user,
                    'password' => Hash::make('password'),
                    'role' => 0,
                ]
            );
        }

        // 投稿権限ユーザー
        $authorizedUsers = ['user3', 'user4'];

        foreach ($authorizedUsers as $authorizedUser) {
            User::firstOrCreate(
                ['email' => $authorizedUser.'@email.com'],
                [
                    'name' => $authorizedUser,
                    'password' => Hash::make('password'),
                    'role' => 1,
                ]
            );
        }
    }
}
