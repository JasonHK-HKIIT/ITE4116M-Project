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
        $this->call([
            InstituteCampusSeeder::class,
            ProgrammeSeeder::class,
            ModuleSeeder::class,
            ProgrammeModuleSeeder::class,
            ClassSeeder::class,
            UserSeeder::class,
            StudentSeeder::class,
            StudentClassSeeder::class,
            NewsArticleSeeder::class,
            CarouselSlideSeeder::class,
            ResourceSeeder::class,
            ActivitySeeder::class,
            CalendarEventSeeder::class,
        ]);
    }
}
