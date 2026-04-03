<?php

namespace Tests\Feature\Api;

use App\Enums\NewsArticleStatus;
use App\Models\NewsArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_latest_published_news_filtered_by_keyword(): void
    {
        $older = NewsArticle::factory()->create([
            'status' => NewsArticleStatus::Published,
            'published_on' => '2026-03-01',
            'slug' => 'older-news',
        ]);
        $older->newsArticleTranslation()->create([
            'locale' => 'en',
            'title' => 'Portal maintenance notice',
            'content' => 'Scheduled maintenance for student portal access.',
        ]);

        $newer = NewsArticle::factory()->create([
            'status' => NewsArticleStatus::Published,
            'published_on' => '2026-04-01',
            'slug' => 'newer-news',
        ]);
        $newer->newsArticleTranslation()->create([
            'locale' => 'en',
            'title' => 'Exam announcement',
            'content' => 'Portal now shows the updated exam timetable.',
        ]);

        $draft = NewsArticle::factory()->draft()->create([
            'slug' => 'draft-news',
        ]);
        $draft->newsArticleTranslation()->create([
            'locale' => 'en',
            'title' => 'Draft portal note',
            'content' => 'This should never be exposed in API response.',
        ]);

        $response = $this->getJson('/api/news?keyword=portal&locale=en&limit=10');

        $response->assertOk();

        $data = $response->json('data');

        $this->assertCount(2, $data);
        $this->assertSame(['newer-news', 'older-news'], array_column($data, 'slug'));
        $this->assertSame('portal', $response->json('meta.keyword'));
    }

    public function test_it_applies_limit_and_returns_latest_when_keyword_missing(): void
    {
        $first = NewsArticle::factory()->create([
            'status' => NewsArticleStatus::Published,
            'published_on' => '2026-02-01',
            'slug' => 'first-news',
        ]);
        $first->newsArticleTranslation()->create([
            'locale' => 'en',
            'title' => 'First item',
            'content' => 'First content',
        ]);

        $second = NewsArticle::factory()->create([
            'status' => NewsArticleStatus::Published,
            'published_on' => '2026-03-01',
            'slug' => 'second-news',
        ]);
        $second->newsArticleTranslation()->create([
            'locale' => 'en',
            'title' => 'Second item',
            'content' => 'Second content',
        ]);

        $response = $this->getJson('/api/news?locale=en&limit=1');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('second-news', $response->json('data.0.slug'));
    }
}
