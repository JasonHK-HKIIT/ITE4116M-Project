<?php

namespace Database\Seeders;

use App\Enums\Role;
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

        User::firstOrCreate(
            [
                'username' => '240155170',
            ],
            [
                'password' => 'qwerasdf',
                'family_name' => 'Hui',
                'given_name' => 'Ho Fung Matthew'
            ]
        );


        User::firstOrCreate(
            [
                'username' => 'admin',
            ],
            [
                'password' => 'letmein',
                'family_name' => '',
                'given_name' => 'Admin',
                'role' => Role::ADMIN,
            ]
        );

        $this->call(
            [
                NewsArticleSeeder::class
            ]
        );
    }
}
