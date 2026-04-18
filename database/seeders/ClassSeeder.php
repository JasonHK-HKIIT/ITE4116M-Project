<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institute = \App\Models\Institute::all();
        $campuses = \App\Models\Campus::all();
        $programme = \App\Models\Programme::all();

        $classes = [
            // 2024/2025 Academic Year
            // SE
            ['academic_year' => 2024, 'institute_id' => $institute[2]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[1]->id, 'class_code' => '1A'],
            ['academic_year' => 2024, 'institute_id' => $institute[2]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[1]->id, 'class_code' => '1B'],
            ['academic_year' => 2024, 'institute_id' => $institute[2]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[1]->id, 'class_code' => '1C'],

            //DI
            ['academic_year' => 2024, 'institute_id' => $institute[1]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[0]->id, 'class_code' => '1A'],
            ['academic_year' => 2024, 'institute_id' => $institute[1]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[0]->id, 'class_code' => '1B'],

            // Year 2 Classes
            ['academic_year' => 2024, 'institute_id' => $institute[2]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[1]->id, 'class_code' => '2A'],
            ['academic_year' => 2024, 'institute_id' => $institute[2]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[1]->id, 'class_code' => '2B'],
            ['academic_year' => 2024, 'institute_id' => $institute[2]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[1]->id, 'class_code' => '2C'],

            ['academic_year' => 2024, 'institute_id' => $institute[1]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[0]->id, 'class_code' => '2A'],
            ['academic_year' => 2024, 'institute_id' => $institute[1]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[0]->id, 'class_code' => '2B'],

            // Year 3 Classes
            ['academic_year' => 2024, 'institute_id' => $institute[2]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[1]->id, 'class_code' => '3A'],

            ['academic_year' => 2024, 'institute_id' => $institute[1]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[0]->id, 'class_code' => '3B'],

            // 2025/2026 Academic Year
            // Year 1 Classes 
            ['academic_year' => 2025, 'institute_id' => $institute[2]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[1]->id, 'class_code' => '1A'],
            ['academic_year' => 2025, 'institute_id' => $institute[2]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[1]->id, 'class_code' => '1B'],
            ['academic_year' => 2025, 'institute_id' => $institute[2]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[1]->id, 'class_code' => '1C'],

            ['academic_year' => 2025, 'institute_id' => $institute[1]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[0]->id, 'class_code' => '1A'],
            ['academic_year' => 2025, 'institute_id' => $institute[1]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[0]->id, 'class_code' => '1B'],

            // Year 2 Classes
            ['academic_year' => 2025, 'institute_id' => $institute[2]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[1]->id, 'class_code' => '2A'],
            ['academic_year' => 2025, 'institute_id' => $institute[2]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[1]->id, 'class_code' => '2B'],
            ['academic_year' => 2025, 'institute_id' => $institute[2]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[1]->id, 'class_code' => '2C'],

            ['academic_year' => 2025, 'institute_id' => $institute[1]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[0]->id, 'class_code' => '2A'],
            ['academic_year' => 2025, 'institute_id' => $institute[1]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[0]->id, 'class_code' => '2B'],

            // Year 3 Classes
            ['academic_year' => 2025, 'institute_id' => $institute[2]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[1]->id, 'class_code' => '3A'],

            ['academic_year' => 2025, 'institute_id' => $institute[1]->id, 'campus_id' => $campuses[0]->id, 'programme_id' => $programme[0]->id, 'class_code' => '3B'],

        ];

        foreach ($classes as $data) {
            \App\Models\ClassModel::firstOrCreate($data);
        }
    }
}
