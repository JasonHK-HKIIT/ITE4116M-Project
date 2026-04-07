<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\InstituteCampus;
use App\Models\Student;
use App\Models\User;
use App\Models\UserPermission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            [
                'username' => '240141706',
            ],
            [
                'password' => 'letmein',
                'family_name' => 'KWOK',
                'given_name' => 'Chi Leong'
            ]
        );

        User::firstOrCreate(
            [
                'username' => '240155170',
            ],
            [
                'password' => 'qwerasdf',
                'family_name' => 'Hui',
                'given_name' => 'Ho Fung Matthew',
                'chinese_name' => '許皓峰',
            ]
        );

        User::firstOrCreate(
            [
                'username' => 'admin',
            ],
            [
                'password' => 'letmein',
                'family_name' => '',
                'given_name' => 'Admin',
                'role' => Role::ADMIN,
            ]
        );

        $staff = User::firstOrCreate(
            [
                'username' => 'staff',
            ],
            [
                'password' => 'letmein',
                'family_name' => 'Staff',
                'given_name' => 'User',
                'role' => Role::STAFF,
            ]
        );

        foreach (Permission::values() as $permission)
        {
            UserPermission::firstOrCreate([
                'user_id' => $staff->id,
                'permission' => $permission,
            ]);
        }

        $this->call([
            InstituteCampusSeeder::class,
            StudentSeeder::class,
            ProgrammeSeeder::class,
            ModuleSeeder::class,
            ProgrammeModuleSeeder::class,
            ClassSeeder::class,
            StudentClassSeeder::class,
            UserSeeder::class,
            NewsArticleSeeder::class,
            ResourceSeeder::class,
            ActivitySeeder::class,
            CalendarEventSeeder::class,
        ]);
    }
}
