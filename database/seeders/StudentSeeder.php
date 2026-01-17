<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institute = \App\Models\Institute::first();
        $campus = \App\Models\Campus::first();

        $user = \App\Models\User::where('username', '240155170')->first();
        \App\Models\Student::firstOrCreate(
            ['user_id' => $user->id],
            [
                'institute_id' => $institute->id,
                'campus_id' => $campus->id,
                'gender' => 'Male',
                'date_of_birth' => '1996-06-03',
                'nationality' => 'Hong Kong SAR China',
                'mother_tongue' => 'Cantonese',
                'tel_no' => null,
                'mobile_no' => '65557890',
                'address' => 'Flat C, 50/F, Mei Lam Court Phase 10, City One Shatin Sha Tin, New Territories, Hong Kong',
            ]
        );
    }
}
