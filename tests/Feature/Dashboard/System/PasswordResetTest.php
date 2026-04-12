<?php

namespace Tests\Feature\Dashboard\System;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Volt\Volt;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_reset_password_for_non_admin_user(): void
    {
        $admin = User::factory()->create([
            'role' => Role::ADMIN,
            'password' => Hash::make('password'),
        ]);

        $target = User::factory()->create([
            'role' => Role::STAFF,
            'username' => 'staff-user',
            'password' => Hash::make('old-password'),
        ]);

        $this->actingAs($admin);

        $response = Volt::test('pages::dashboard.system.password')
            ->set('username', $target->username)
            ->set('password', 'new-password')
            ->set('password_confirmation', 'new-password')
            ->call('resetPassword');

        $response->assertHasNoErrors();
        $this->assertTrue(Hash::check('new-password', $target->refresh()->password));
    }

    public function test_admin_cannot_reset_password_for_admin_user(): void
    {
        $admin = User::factory()->create([
            'role' => Role::ADMIN,
        ]);

        $targetAdmin = User::factory()->create([
            'role' => Role::ADMIN,
            'username' => 'another-admin',
            'password' => Hash::make('old-password'),
        ]);

        $this->actingAs($admin);

        $response = Volt::test('pages::dashboard.system.password')
            ->set('username', $targetAdmin->username)
            ->set('password', 'new-password')
            ->set('password_confirmation', 'new-password')
            ->call('resetPassword');

        $response->assertHasErrors(['username']);
        $this->assertTrue(Hash::check('old-password', $targetAdmin->refresh()->password));
    }

    public function test_non_admin_user_cannot_access_admin_password_reset_page(): void
    {
        $staff = User::factory()->create([
            'role' => Role::STAFF,
        ]);

        $response = $this->actingAs($staff)->get('/dashboard/system/password');

        $response->assertForbidden();
    }

    public function test_admin_reset_requires_existing_username(): void
    {
        $admin = User::factory()->create([
            'role' => Role::ADMIN,
        ]);

        $this->actingAs($admin);

        $response = Volt::test('pages::dashboard.system.password')
            ->set('username', 'missing-user')
            ->set('password', 'new-password')
            ->set('password_confirmation', 'new-password')
            ->call('resetPassword');

        $response->assertHasErrors(['username']);
    }

    public function test_admin_reset_requires_password_confirmation_to_match(): void
    {
        $admin = User::factory()->create([
            'role' => Role::ADMIN,
        ]);

        $target = User::factory()->create([
            'role' => Role::STUDENT,
            'username' => 'student-user',
        ]);

        $this->actingAs($admin);

        $response = Volt::test('pages::dashboard.system.password')
            ->set('username', $target->username)
            ->set('password', 'new-password')
            ->set('password_confirmation', 'another-password')
            ->call('resetPassword');

        $response->assertHasErrors(['password']);
    }
}
