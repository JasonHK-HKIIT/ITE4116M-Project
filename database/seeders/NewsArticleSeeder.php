<?php

namespace Database\Seeders;

use App\Helpers\LocalesHelper;
use App\Models\NewsArticle;
use App\Models\NewsArticleTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class NewsArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NewsArticle::factory()
            ->count(50)
            ->has(NewsArticleTranslation::factory()
                ->count(3)
                ->sequence(...Arr::map(LocalesHelper::locales(), fn($item) => ['locale' => $item])))
            ->create();
        
        NewsArticle::factory()
            ->count(5)
            ->draft()
            ->has(NewsArticleTranslation::factory()
                ->count(3)
                ->sequence(...Arr::map(LocalesHelper::locales(), fn($item) => ['locale' => $item])))
            ->create();
    }
}
