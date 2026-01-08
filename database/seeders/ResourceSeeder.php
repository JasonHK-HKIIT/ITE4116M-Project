<?php

namespace Database\Seeders;

use App\Helpers\LocalesHelper;
use App\Models\Resource;
use App\Models\ResourceTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Resource::factory()
            ->count(50)
            ->has(ResourceTranslation::factory()
                ->count(3)
                ->resource()
                ->sequence(...Arr::map(LocalesHelper::locales(), fn($item) => ['locale' => $item])))
            ->create();
    }
}
