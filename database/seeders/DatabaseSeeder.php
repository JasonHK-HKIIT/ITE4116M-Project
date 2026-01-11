<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\InstituteCampus;
use App\Models\Student;
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
                'given_name' => 'Ho Fung Matthew',
                'chinese_name' => '許皓峰',
            ]
        );

        $user240155170 = User::where('username', '240155170')->first();
        $institute = \App\Models\Institute::firstOrCreate([]);
        $campusModel = \App\Models\Campus::firstOrCreate([]);
        $campus = \App\Models\InstituteCampus::firstOrCreate([
            'institute_id' => $institute->id,
            'campus_id' => $campusModel->id,
        ]);

        Student::firstOrCreate(
            ['user_id' => $user240155170->id],
            [
                'institute_campus_id' => $campus->id,
                'gender' => 'Male',
                'date_of_birth' => '1996-06-03',
                'nationality' => 'Hong Kong SAR China',
                'mother_tongue' => 'Tofu',
                'tel_no' => null,
                'mobile_no' => '65557890',
                'address' => 'My home, My city, My land, My country',
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
                NewsArticleSeeder::class,
                ResourceSeeder::class,
                ActivitySeeder::class,
                InformationCentreSeeder::class,
            ]
        );
    }
}
