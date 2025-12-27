<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            [
                'username' => '240141706',
            ],
            [
                'password' => 'letmein',
                'family_name' => 'KWOK',
                'given_name' => 'Chi Leong'
            ]
        );

        $this->call(
            [
                NewsArticleSeeder::class
            ]);
    }
}
