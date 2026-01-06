<?php

namespace Database\Seeders;

use App\Enums\Language;
use App\Models\NewsArticle;
use App\Models\NewsArticleTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

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
                ->sequence(
                    ['locale' => Language::ENGLISH->value],
                    ['locale' => Language::CHINESE_TRADITIONAL->value],
                    ['locale' => Language::CHINESE_SIMPLIFIED->value]))
            ->create();
        
        NewsArticle::factory()
            ->count(5)
            ->draft()
            ->has(NewsArticleTranslation::factory()
                ->count(3)
                ->sequence(
                    ['locale' => Language::ENGLISH->value],
                    ['locale' => Language::CHINESE_TRADITIONAL->value],
                    ['locale' => Language::CHINESE_SIMPLIFIED->value]))
            ->create();
    }
}
