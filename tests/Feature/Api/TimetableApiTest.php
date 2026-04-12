<?php

namespace Tests\Feature\Api;

use App\Enums\CalendarEventType;
use App\Enums\Role;
use App\Models\CalendarEvent;
use App\Models\Campus;
use App\Models\ClassModel;
use App\Models\Institute;
use App\Models\Programme;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimetableApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_only_the_student_visible_timetable_events(): void
    {
        $instituteA = Institute::create();
        $instituteB = Institute::create();

        $campus = Campus::create();

        $programmeA = Programme::create([
            'institute_id' => $instituteA->id,
            'programme_code' => 'HDSE',
        ]);

        $programmeB = Programme::create([
            'institute_id' => $instituteB->id,
            'programme_code' => 'HDIT',
        ]);

        $classA = ClassModel::create([
            'academic_year' => 2025,
            'institute_id' => $instituteA->id,
            'campus_id' => $campus->id,
            'programme_id' => $programmeA->id,
            'class_code' => 'IT114105',
        ]);

        $classB = ClassModel::create([
            'academic_year' => 2025,
            'institute_id' => $instituteB->id,
            'campus_id' => $campus->id,
            'programme_id' => $programmeB->id,
            'class_code' => 'IT114999',
        ]);

        $studentUser = User::create([
            'username' => '240155170',
            'password' => 'password',
            'role' => Role::STUDENT,
        ]);

        $otherUser = User::create([
            'username' => '240155171',
            'password' => 'password',
            'role' => Role::STUDENT,
        ]);

        $student = Student::create([
            'user_id' => $studentUser->id,
            'institute_id' => $instituteA->id,
            'campus_id' => $campus->id,
        ]);

        $otherStudent = Student::create([
            'user_id' => $otherUser->id,
            'institute_id' => $instituteB->id,
            'campus_id' => $campus->id,
        ]);

        $student->classes()->attach($classA->id);
        $otherStudent->classes()->attach($classB->id);

        $windowStart = now()->addDay()->startOfDay();

        $classEventId = CalendarEvent::create([
            'class_id' => $classA->id,
            'type' => CalendarEventType::CLASS_TYPE,
            'title' => 'COMP1001',
            'start_at' => $windowStart,
            'end_at' => $windowStart->copy()->addHour(),
        ])->id;

        CalendarEvent::create([
            'class_id' => $classB->id,
            'type' => CalendarEventType::CLASS_TYPE,
            'title' => 'COMP9999',
            'start_at' => $windowStart->copy()->addHours(2),
            'end_at' => $windowStart->copy()->addHours(3),
        ]);

        $activityEventId = CalendarEvent::create([
            'student_id' => $student->id,
            'type' => CalendarEventType::ACTIVITY,
            'title' => 'Coding Club',
            'start_at' => $windowStart->copy()->addDays(1),
            'end_at' => $windowStart->copy()->addDays(1)->addHour(),
        ])->id;

        CalendarEvent::create([
            'student_id' => $otherStudent->id,
            'type' => CalendarEventType::ACTIVITY,
            'title' => 'Other Student Activity',
            'start_at' => $windowStart->copy()->addDays(2),
            'end_at' => $windowStart->copy()->addDays(2)->addHour(),
        ]);

        $publicHolidayId = CalendarEvent::create([
            'type' => CalendarEventType::PUBLIC_HOLIDAY,
            'title' => 'Public Holiday',
            'start_at' => $windowStart->copy()->addDays(3),
            'end_at' => $windowStart->copy()->addDays(3)->addHour(),
        ])->id;

        $instituteHolidayId = CalendarEvent::create([
            'type' => CalendarEventType::INSTITUTE_HOLIDAY,
            'institute_id' => $instituteA->id,
            'title' => 'Institute A Holiday',
            'start_at' => $windowStart->copy()->addDays(4),
            'end_at' => $windowStart->copy()->addDays(4)->addHour(),
        ])->id;

        $globalInstituteHolidayId = CalendarEvent::create([
            'type' => CalendarEventType::INSTITUTE_HOLIDAY,
            'institute_id' => null,
            'title' => 'Global Institute Holiday',
            'start_at' => $windowStart->copy()->addDays(5),
            'end_at' => $windowStart->copy()->addDays(5)->addHour(),
        ])->id;

        CalendarEvent::create([
            'type' => CalendarEventType::INSTITUTE_HOLIDAY,
            'institute_id' => $instituteB->id,
            'title' => 'Institute B Holiday',
            'start_at' => $windowStart->copy()->addDays(6),
            'end_at' => $windowStart->copy()->addDays(6)->addHour(),
        ]);

        CalendarEvent::create([
            'class_id' => $classA->id,
            'type' => CalendarEventType::CLASS_TYPE,
            'title' => 'Out Of Range Class',
            'start_at' => $windowStart->copy()->addDays(180),
            'end_at' => $windowStart->copy()->addDays(180)->addHour(),
        ]);

        $response = $this->getJson('/api/timetable?user_id='.$studentUser->id.'&start_date='.$windowStart->toDateString().'&end_date='.$windowStart->copy()->addDays(20)->toDateString());

        $response->assertOk();
        $response->assertJsonPath('meta.user_id', $studentUser->id);
        $response->assertJsonPath('meta.student_id', $student->id);

        $ids = collect($response->json('data'))->pluck('id')->all();

        $this->assertSame([
            $classEventId,
            $activityEventId,
            $publicHolidayId,
            $instituteHolidayId,
            $globalInstituteHolidayId,
        ], $ids);
    }

    public function test_it_validates_date_range_constraints(): void
    {
        $user = User::create([
            'username' => '240199999',
            'password' => 'password',
            'role' => Role::STUDENT,
        ]);

        $institute = Institute::create();
        $campus = Campus::create();

        Student::create([
            'user_id' => $user->id,
            'institute_id' => $institute->id,
            'campus_id' => $campus->id,
        ]);

        $invalidOrder = $this->getJson('/api/timetable?user_id='.$user->id.'&start_date=2026-04-20&end_date=2026-04-10');
        $invalidOrder->assertStatus(422);

        $tooWideRange = $this->getJson('/api/timetable?user_id='.$user->id.'&start_date=2026-01-01&end_date=2026-06-30');
        $tooWideRange->assertStatus(422);
    }

    public function test_non_student_user_still_receives_public_holidays(): void
    {
        $user = User::create([
            'username' => 'teacher001',
            'password' => 'password',
            'role' => Role::STAFF,
        ]);

        $windowStart = now()->addDay()->startOfDay();

        $publicHolidayId = CalendarEvent::create([
            'type' => CalendarEventType::PUBLIC_HOLIDAY,
            'title' => 'Public Holiday',
            'start_at' => $windowStart,
            'end_at' => $windowStart->copy()->addHour(),
        ])->id;

        CalendarEvent::create([
            'type' => CalendarEventType::INSTITUTE_HOLIDAY,
            'title' => 'Institute Holiday',
            'start_at' => $windowStart->copy()->addDays(1),
            'end_at' => $windowStart->copy()->addDays(1)->addHour(),
        ]);

        $response = $this->getJson('/api/timetable?user_id='.$user->id.'&start_date='.$windowStart->toDateString().'&end_date='.$windowStart->copy()->addDays(7)->toDateString());

        $response->assertOk();
        $response->assertJsonPath('meta.user_id', $user->id);
        $response->assertJsonPath('meta.student_id', null);
        $response->assertJsonPath('meta.total', 1);

        $ids = collect($response->json('data'))->pluck('id')->all();
        $this->assertSame([$publicHolidayId], $ids);
    }
}
