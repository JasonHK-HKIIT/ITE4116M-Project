<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use App\Models\ClassModel;
use App\Models\User;
use App\Models\Activity;
use App\Enums\CalendarEventType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CalendarEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add data into 240155170 = Software Engineering 2A class
        $user = User::where('username', '240155170')->first();
        $student = $user?->student;

        if (! $student) {
            return;
        }

        $classIds = $student->classes()->pluck('classes.id');

        if ($classIds->isEmpty()) {
            return;
        }

        $weekBase = Carbon::now()->startOfWeek();

        $classes = ClassModel::with('programme.modules')
            ->whereIn('id', $classIds)
            ->get();

        if ($classes->isEmpty()) {
            return;
        }

        $class = $classes->firstWhere('class_code', '2A') ?? $classes->first();

        if (! $class || ! $class->programme) {
            return;
        }

        $modules = $class->programme->modules->values();

        if ($modules->get(0)) {
            $module = $modules->get(0);
            $code = $module->module_code;
            $name = $module->translate('en')?->name ?? $module->name;

            CalendarEvent::create([
                'class_id'    => $class->id,
                'type'        => CalendarEventType::CLASS_TYPE,
                'title'       => $code . ' - ' . $name,
                'description' => 'Scheduled class for module: ' . $name,
                'location'    => 'LW002',
                'instructor'  => 'Dr. Chan',
                'start_at'    => $weekBase->copy()->addDays(0)->setTime(8, 0),
                'end_at'      => $weekBase->copy()->setTime(9, 0),
            ]);
        }

        if ($modules->get(1)) {
            $module = $modules->get(1);
            $code = $module->module_code;
            $name = $module->translate('en')?->name ?? $module->name;

            CalendarEvent::create([
                'class_id'    => $class->id,
                'type'        => CalendarEventType::CLASS_TYPE,
                'title'       => $code . ' - ' . $name,
                'description' => 'Scheduled class for module: ' . $name,
                'location'    => 'LW6M02',
                'instructor'  => 'Dr. Wong',
                'start_at'    => $weekBase->copy()->addDays(0)->setTime(9, 0),
                'end_at'      => $weekBase->copy()->setTime(11, 0),
            ]);
        }

        if ($modules->get(2)) {
            $module = $modules->get(2);
            $code = $module->module_code;
            $name = $module->translate('en')?->name ?? $module->name;

            CalendarEvent::create([
                'class_id'    => $class->id,
                'type'        => CalendarEventType::CLASS_TYPE,
                'title'       => $code . ' - ' . $name,
                'description' => 'Scheduled class for module: ' . $name,
                'location'    => 'LW303',
                'instructor'  => 'Ms. Lee',
                'start_at'    => $weekBase->copy()->addDays(0)->setTime(11, 0),
                'end_at'      => $weekBase->copy()->setTime(12, 30),
            ]);
        }

        if ($modules->get(3)) {
            $module = $modules->get(3);
            $code = $module->module_code;
            $name = $module->translate('en')?->name ?? $module->name;

            CalendarEvent::create([
                'class_id'    => $class->id,
                'type'        => CalendarEventType::CLASS_TYPE,
                'title'       => $code . ' - ' . $name,
                'description' => 'Scheduled class for module: ' . $name,
                'location'    => 'C303',
                'instructor'  => 'Mr. Ho',
                'start_at'    => $weekBase->copy()->addDays(1)->setTime(13, 30),
                'end_at'      => $weekBase->copy()->setTime(15, 0),
            ]);
        }

        if ($modules->get(4)) {
            $module = $modules->get(4);
            $code = $module->module_code;
            $name = $module->translate('en')?->name ?? $module->name;

            CalendarEvent::create([
                'class_id'    => $class->id,
                'type'        => CalendarEventType::CLASS_TYPE,
                'title'       => $code . ' - ' . $name,
                'description' => 'Scheduled class for module: ' . $name,
                'location'    => 'A201',
                'instructor'  => 'Dr. Ng',
                'start_at'    => $weekBase->copy()->addDays(2)->setTime(15, 0),
                'end_at'      => $weekBase->copy()->setTime(16, 0),
            ]);
        }

        if ($modules->get(5)) {
            $module = $modules->get(5);
            $code = $module->module_code;
            $name = $module->translate('en')?->name ?? $module->name;

            CalendarEvent::create([
                'class_id'    => $class->id,
                'type'        => CalendarEventType::CLASS_TYPE,
                'title'       => $code . ' - ' . $name,
                'description' => 'Scheduled class for module: ' . $name,
                'location'    => 'B102',
                'instructor'  => 'Ms. Lam',
                'start_at'    => $weekBase->copy()->addDays(2)->setTime(16, 0),
                'end_at'      => $weekBase->copy()->setTime(17, 0),
            ]);
        }

        // Public holiday
        CalendarEvent::create([
            'class_id'    => null,
            'type'        => CalendarEventType::PUBLIC_HOLIDAY,
            'title'       => 'Public Holiday!',
            'description' => 'hello, I am public holiday!',
            'location'    => null,
            'instructor'  => null,
            'start_at'    => $weekBase->copy()->addDays(5),
            'end_at'      => $weekBase->copy(),
        ]);

        // Institute holiday
        CalendarEvent::create([
            'class_id'    => null,
            'type'        => CalendarEventType::INSTITUTE_HOLIDAY,
            'title'       => 'Institute Holiday!',
            'description' => 'hello, I am institute holiday!',
            'location'    => null,
            'instructor'  => null,
            'start_at'    => $weekBase->copy()->addDays(6),
            'end_at'      => $weekBase->copy(),
        ]);

        // This activity not a real data
        CalendarEvent::create([
            'student_id'  => $student->id,
            'class_id'    => null,
            'type'        => CalendarEventType::ACTIVITY,
            'title'       => 'Student Orientation Day',
            'description' => 'Welcome and orientation session for students.',
            'location'    => 'LW001',
            'instructor'  => 'Mr. Hui',
            'start_at'    => $weekBase->copy()->addDays(3)->setTime(17, 0),
            'end_at'      => $weekBase->copy()->setTime(18, 0),
        ]);

        // This activity not a real data
        CalendarEvent::create([
            'student_id'  => $student->id,
            'class_id'    => null,
            'type'        => CalendarEventType::ACTIVITY,
            'title'       => 'Career Talk: IT Industry',
            'description' => 'Industry sharing session about careers in IT.',
            'location'    => 'LW001',
            'instructor'  => 'Mr. Ho',
            'start_at'    => $weekBase->copy()->addDays(4)->setTime(17, 0),
            'end_at'      => $weekBase->copy()->setTime(18, 0),
        ]);

        // This activity not a real data
        CalendarEvent::create([
            'student_id'  => $student->id,
            'class_id'    => null,
            'type'        => CalendarEventType::ACTIVITY,
            'title'       => 'Campus Open Day',
            'description' => 'Guided tours and introduction to campus facilities.',
            'location'    => 'LW002',
            'instructor'  => 'Ms. Wong',
            'start_at'    => $weekBase->copy()->addDays(7)->setTime(17, 0),
            'end_at'      => $weekBase->copy()->setTime(18, 0),
        ]);

        // This activity not a real data
        CalendarEvent::create([
            'student_id'  => $student->id,
            'class_id'    => null,
            'type'        => CalendarEventType::ACTIVITY,
            'title'       => 'Alumni Sharing Session',
            'description' => 'Alumni share their study and career experience.',
            'location'    => 'LW003',
            'instructor'  => 'Ms. Chung',
            'start_at'    => $weekBase->copy()->addDays(7)->setTime(18, 0),
            'end_at'      => $weekBase->copy()->setTime(19, 0),
        ]);

        //$activity = Activity::where('activity_code', 'ACT001')->first(); <--to Stella
        $activity = Activity::whereTranslation('title', 'Intro to Laravel', 'en')->first();

        if ($activity) {
            $fromDate = $activity->time_slot_from_date;
            $fromTime = $activity->time_slot_from_time;
            $toDate   = $activity->time_slot_to_date;
            $toTime   = $activity->time_slot_to_time;

            $startAt = $fromDate->copy()->setTime($fromTime->hour, $fromTime->minute);
            $endAt = $toDate->copy()->setTime($toTime->hour, $toTime->minute);

            CalendarEvent::create([
                'student_id'  => $student->id,
                'class_id'    => null,
                'type'        => CalendarEventType::ACTIVITY,
                'title'       => $activity->translate('en')->title,
                'description' => $activity->translate('en')->description,
                'location'    => $activity->venue,
                'instructor'  => $activity->instructor,
                'start_at'    => $startAt,
                'end_at'      => $endAt,
            ]);
        }
    }
}
