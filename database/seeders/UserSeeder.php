<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var User */
        $user = User::create(
            [
                'username' => 'admin',
                'password' => 'letmein',
                'family_name' => '',
                'given_name' => 'Admin',
                'role' => Role::ADMIN,
            ]);

        /** @var User */
        $user = User::create(
            [
                'username' => 'editor',
                'password' => 'let-me-edit',
                'role' => Role::STAFF,
                'family_name' => '',
                'given_name' => 'Editor',
            ]);
        $user->permissions()->createMany(
            [
                ['permission' => Permission::Calendar],
                ['permission' => Permission::NewsAnnouncement],
                ['permission' => Permission::ResourcesCentre],
            ]);
    }
}
