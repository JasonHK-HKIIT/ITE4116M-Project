<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\ClassModel;
use App\Models\Institute;
use App\Models\Programme;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institute = Institute::whereTranslation('name', 'Hong Kong Institute of Information Technology', 'en')->first();
        $campus = Campus::whereTranslation('name', 'Lee Wai Lee', 'en')->first();
        $programme = Programme::where('programme_code', 'IT114105')->first();

        /** @var User */
        $user = User::create(
            [
                'username' => '240155170',
                'password' => 'qwerasdf',
                'family_name' => 'Hui',
                'given_name' => 'Ho Fung Matthew',
                'chinese_name' => '許皓峰',
            ]);
        $user->student()->create(
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
            ]);
        $user->student->classes()->sync(
            [
                ClassModel::where('academic_year', 2024)->where('class_code', '1A')->where('programme_id', $programme->id)->first()->id,
                ClassModel::where('academic_year', 2025)->where('class_code', '2A')->where('programme_id', $programme->id)->first()->id,
            ]);

        /** @var User */
        $user = User::firstOrCreate(
            [
                'username' => '240141706',
                'password' => 'letmein',
                'family_name' => 'KWOK',
                'given_name' => 'Chi Leong'
            ]);
        $user->student()->create(
            [
                'institute_id' => $institute->id,
                'campus_id' => $campus->id,
                'gender' => 'Male',
                'date_of_birth' => '2000-12-25',
                'nationality' => 'Hong Kong SAR China',
                'mother_tongue' => 'Cantonese',
                'tel_no' => null,
                'mobile_no' => '65534567',
                'address' => 'Flat B, 60/F, Mei Lam Court Phase 20, City Two Shatin Sha Tin, Old Territories, Hong Kong',
            ]);
    }
}
