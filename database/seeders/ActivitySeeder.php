<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Activity::factory()->count(20)->create();

        Activity::create([
            'campus'            => 'Not specified',
            'discipline'        => 'IT',
            'activity_code'     => 'ACT0001',
            'activity_type'     => 'Workshop',
            'title'             => 'Intro to Laravel',
            'instructor'        => 'John Doe',
            'responsible_staff' => 'Jane Smith',
            'execution_from'    => '2026-01-15',
            'execution_to'      => '2026-01-20',
            'time_slot_from'    => '2026-01-15 09:30',
            'time_slot_to'      => '2026-01-15 10:30',
            'duration_hours'    => 1.0,
            'description'       => 'A beginner-friendly workshop introducing Laravel basics.',
            'attribute'         => 'Beginner',
            'swpd_programme'    => true,
            'venue'             => 'Room 101',
            'venue_remark'      => 'Bring your own laptop',
            'capacity'          => 30,
            'registered'        => 12,
            'has_vacancy'       => true,
            'total_amount'      => 500.00,
            'included_deposit'  => 100.00,
            'attachment'        => 'intro_laravel.pdf',
        ]);
    }

}
