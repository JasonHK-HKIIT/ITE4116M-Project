<?php

namespace Tests\Feature\Api;

use App\Models\Activity;
use App\Models\Campus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentActivitiesApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_active_and_upcoming_activities_with_filters(): void
    {
        $campus = Campus::factory()->create();
        $campus->campusTranslation()->create([
            'locale' => 'en',
            'name' => 'Main Campus',
        ]);

        $otherCampus = Campus::factory()->create();
        $otherCampus->campusTranslation()->create([
            'locale' => 'en',
            'name' => 'Other Campus',
        ]);

        $active = Activity::factory()->create([
            'activity_type' => 'Student Groups',
            'campus_id' => $campus->id,
            'discipline' => 'IT',
            'execution_from' => now()->subDay()->toDateString(),
            'execution_to' => now()->addDays(2)->toDateString(),
            'registered' => 5,
            'capacity' => 20,
        ]);
        $active->activityTranslation()->create([
            'locale' => 'en',
            'title' => 'Coding Club Meetup',
            'description' => 'Hands-on coding practice for students.',
            'venue_remark' => 'Bring your laptop.',
        ]);

        $upcoming = Activity::factory()->create([
            'activity_type' => 'Student Groups',
            'campus_id' => $campus->id,
            'discipline' => 'IT',
            'execution_from' => now()->addDay()->toDateString(),
            'execution_to' => now()->addDays(4)->toDateString(),
            'registered' => 20,
            'capacity' => 20,
        ]);
        $upcoming->activityTranslation()->create([
            'locale' => 'en',
            'title' => 'Coding Bootcamp',
            'description' => 'Join our coding bootcamp sessions.',
            'venue_remark' => 'Lab 301.',
        ]);

        $past = Activity::factory()->create([
            'activity_type' => 'Student Groups',
            'campus_id' => $campus->id,
            'discipline' => 'IT',
            'execution_from' => now()->subDays(5)->toDateString(),
            'execution_to' => now()->subDay()->toDateString(),
        ]);
        $past->activityTranslation()->create([
            'locale' => 'en',
            'title' => 'Coding Event (Past)',
            'description' => 'This should be filtered out.',
            'venue_remark' => 'Expired.',
        ]);

        $otherFilter = Activity::factory()->create([
            'activity_type' => 'Career Development Activities',
            'campus_id' => $otherCampus->id,
            'discipline' => 'Business',
            'execution_from' => now()->addDay()->toDateString(),
            'execution_to' => now()->addDays(2)->toDateString(),
        ]);
        $otherFilter->activityTranslation()->create([
            'locale' => 'en',
            'title' => 'Coding Career Talk',
            'description' => 'Should be excluded by filters.',
            'venue_remark' => 'Hall B.',
        ]);

        $response = $this->getJson('/api/activities?keyword=coding&locale=en&limit=10&activity_type=Student%20Groups&campus_id='.$campus->id.'&discipline=IT');

        $response->assertOk();

        $data = $response->json('data');

        $this->assertCount(2, $data);
        $this->assertSame([$active->id, $upcoming->id], array_column($data, 'id'));
        $this->assertSame('Main Campus', $data[0]['campus_name']);
        $this->assertTrue($data[0]['has_vacancy']);
        $this->assertFalse($data[1]['has_vacancy']);
        $this->assertSame('coding', $response->json('meta.keyword'));
        $this->assertSame('Student Groups', $response->json('meta.filters.activity_type'));
        $this->assertSame($campus->id, $response->json('meta.filters.campus_id'));
        $this->assertSame('IT', $response->json('meta.filters.discipline'));
    }

    public function test_it_applies_limit_and_validates_filter_values(): void
    {
        $campus = Campus::factory()->create();
        $campus->campusTranslation()->create([
            'locale' => 'en',
            'name' => 'Main Campus',
        ]);

        $first = Activity::factory()->create([
            'activity_type' => 'Student Groups',
            'campus_id' => $campus->id,
            'discipline' => 'IT',
            'execution_from' => now()->addDay()->toDateString(),
            'execution_to' => now()->addDays(2)->toDateString(),
        ]);
        $first->activityTranslation()->create([
            'locale' => 'en',
            'title' => 'Robotics Team A',
            'description' => 'Group A',
            'venue_remark' => 'Room 201',
        ]);

        $second = Activity::factory()->create([
            'activity_type' => 'Student Groups',
            'campus_id' => $campus->id,
            'discipline' => 'IT',
            'execution_from' => now()->addDays(3)->toDateString(),
            'execution_to' => now()->addDays(4)->toDateString(),
        ]);
        $second->activityTranslation()->create([
            'locale' => 'en',
            'title' => 'Robotics Team B',
            'description' => 'Group B',
            'venue_remark' => 'Room 202',
        ]);

        $response = $this->getJson('/api/activities?keyword=robotics&locale=en&limit=1');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame($first->id, $response->json('data.0.id'));

        $invalid = $this->getJson('/api/activities?discipline=Unknown');

        $invalid->assertStatus(422);
    }
}
