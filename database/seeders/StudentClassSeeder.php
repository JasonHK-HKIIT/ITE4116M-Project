<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::where('username', '240155170')->first();
        $programme = \App\Models\Programme::where('programme_code', 'IT114105')->first();// Higher Diploma in Software Engineering
        if ($user) {
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            if ($student) {
                // 2024/2025 1A 
                $class1A = \App\Models\ClassModel::where([
                    'academic_year' => 2024,
                    'class_code' => '1A',
                    'programme_id' => $programme->id,
                ])->first();
                // 2025/2026 2A
                $class2A = \App\Models\ClassModel::where([
                    'academic_year' => 2025,
                    'class_code' => '2A',
                    'programme_id' => $programme->id,
                ])->first();
                $classIds = [];
                if ($class1A) $classIds[] = $class1A->id;
                if ($class2A) $classIds[] = $class2A->id;
                if ($classIds) {
                    $student->classes()->syncWithoutDetaching($classIds);
                }
            }
        }
    }
}
