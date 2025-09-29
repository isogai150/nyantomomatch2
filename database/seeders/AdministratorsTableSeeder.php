<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Administrator;

class AdministratorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Administrator::firstOrCreate(
            ['email' => 'test@email.com'],
            [
                'name' => 'test',
                'password' => Hash::make('password'),
            ]
        );
    }
}
