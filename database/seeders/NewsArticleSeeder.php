<?php

namespace Database\Seeders;

use App\Enums\Language;
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
            ->count(60)
            ->sequence(
                ['language' => Language::en->value],
                ['language' => Language::zh_HK->value],
                ['language' => Language::zh_CN->value])
            ->create();
        
        NewsArticle::factory()
            ->count(6)
            ->unpublished()
            ->sequence(
                ['language' => Language::en->value],
                ['language' => Language::zh_HK->value],
                ['language' => Language::zh_CN->value])
            ->create();
    }
}
