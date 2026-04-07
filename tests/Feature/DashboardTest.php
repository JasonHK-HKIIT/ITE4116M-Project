<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get(route('dashboard.home'));
        $response->assertRedirect(route('login'));
    }

    public function test_staff_users_can_visit_the_dashboard_home(): void
    {
        $user = User::create([
            'username' => 'staff-user',
            'password' => 'password',
            'family_name' => 'Staff',
            'given_name' => 'User',
            'role' => Role::STAFF,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('dashboard.home'));
        $response->assertStatus(200);
    }

    public function test_student_users_cannot_visit_the_dashboard_home(): void
    {
        $user = User::create([
            'username' => 'student-user',
            'password' => 'password',
            'family_name' => 'Student',
            'given_name' => 'User',
            'role' => Role::STUDENT,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('dashboard.home'));
        $response->assertForbidden();
    }
}
