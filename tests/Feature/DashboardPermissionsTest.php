<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardPermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_module_route_without_user_permissions_rows(): void
    {
        $admin = User::create([
            'username' => 'admin-user',
            'password' => 'password',
            'family_name' => 'Admin',
            'given_name' => 'User',
            'role' => Role::ADMIN,
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('dashboard.calendar.manage'));

        $response->assertOk();
    }

    public function test_staff_with_module_permission_can_access_module_routes(): void
    {
        $staff = User::create([
            'username' => 'staff-with-permission',
            'password' => 'password',
            'family_name' => 'Staff',
            'given_name' => 'Allowed',
            'role' => Role::STAFF,
        ]);

        UserPermission::create([
            'user_id' => $staff->id,
            'permission' => 'calendar',
        ]);

        $this->actingAs($staff);

        $response = $this->get(route('dashboard.calendar.manage'));

        $response->assertOk();
    }

    public function test_staff_without_module_permission_gets_forbidden_and_hidden_sidebar_section(): void
    {
        $staff = User::create([
            'username' => 'staff-without-permission',
            'password' => 'password',
            'family_name' => 'Staff',
            'given_name' => 'Denied',
            'role' => Role::STAFF,
        ]);

        $this->actingAs($staff);

        $calendarResponse = $this->get(route('dashboard.calendar.manage'));
        $homeResponse = $this->get(route('dashboard.home'));

        $calendarResponse->assertForbidden();
        $homeResponse->assertOk();
        $homeResponse->assertDontSee('Calendar');
    }
}
