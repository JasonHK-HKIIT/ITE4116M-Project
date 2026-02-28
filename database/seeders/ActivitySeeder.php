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
            'campus_id'         => 1,
            'activity_type'     => 'Workshop',
            'title:en'          => 'Intro to Laravel',
            'title:zh'          => 'Laravel 入門',
            'description:en'    => 'A beginner-friendly workshop introducing Laravel basics.',
            'description:zh'    => '初學者友善的Laravel基礎工作坊。',
            'discipline:en'     => 'IT',
            'discipline:zh'     => '資訊技術',
            'attribute:en'      => 'Beginner',
            'attribute:zh'      => '初級',
            'instructor'        => 'Tom',
            'responsible_staff' => 'Joe',
            'execution_from'    => '2026-01-15',
            'execution_to'      => '2026-01-20',
            'time_slot_from_date' => '2026-01-15',
            'time_slot_from_time' => '09:30',
            'time_slot_to_date'   => '2026-01-15',
            'time_slot_to_time'   => '10:30',
            'duration_hours'    => 1.0,
            'swpd_programme'    => true,
            'venue'             => 'Room 101',
            'venue_remark'      => 'Bring your own laptop',
            'capacity'          => 30,
            'registered'        => 12,
            'total_amount'      => 500.00,
            'included_deposit'  => 100.00,
            'attachment'        => 'intro_laravel.pdf',
        ]);
    }

}
