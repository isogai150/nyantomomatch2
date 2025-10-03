<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            AdministratorsTableSeeder::class,
            AuthoritiesTableSeeder::class,
            PairsTableSeeder::class,
            MessagesTableSeeder::class,
            PostsTableSeeder::class,
            PostImagesTableSeeder::class,
            PostVideosTableSeeder::class,
            FavoritesTableSeeder::class,
            Message_reports::class,
            PostReportsTableSeeder::class,
            TransfersTableSeeder::class,
            TransferDocumentsTableSeeder::class,
        ]);
    }
}
