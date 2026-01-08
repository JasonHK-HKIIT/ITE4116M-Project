<?php

namespace Database\Seeders;

use App\Models\Resource;
use App\Models\ResourceTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
                ->sequence(
                    ['locale' => 'en'],
                    ['locale' => 'zh-HK'],
                    ['locale' => 'zh-CN']))
            ->create();
    }
}
