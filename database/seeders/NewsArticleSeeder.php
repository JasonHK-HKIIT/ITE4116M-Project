<?php

namespace Database\Seeders;

use App\Models\NewsArticle;
use App\Models\NewsArticleContent;
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
            ->has(NewsArticleContent::factory()
                ->count(3)
                ->sequence(
                    ['language' => "en"],
                    ['language' => "zh_HK"],
                    ['language' => "zh_CN"]))
            ->create();
        
        NewsArticle::factory()
            ->count(5)
            ->unpublished()
            ->has(NewsArticleContent::factory()
                ->count(3)
                ->sequence(
                    ['language' => "en"],
                    ['language' => "zh_HK"],
                    ['language' => "zh_CN"]))
            ->create();
    }
}
