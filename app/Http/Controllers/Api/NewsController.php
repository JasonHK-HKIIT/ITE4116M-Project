<?php

namespace App\Http\Controllers\Api;

use App\Enums\Language;
use App\Enums\NewsArticleStatus;
use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NewsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'keyword' => ['nullable', 'string', 'max:120'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:20'],
            'locale' => ['nullable', 'string', Rule::in(Language::values())],
        ]);

        $keyword = trim((string) ($validated['keyword'] ?? ''));
        $limit = (int) ($validated['limit'] ?? 5);
        $locale = $validated['locale'] ?? app()->getLocale();
        $portalBaseUrl = rtrim((string) config('app.url'), '/');

        $articles = NewsArticle::query()
            ->where('status', NewsArticleStatus::Published)
            ->whereNotNull('published_on')
            ->with([
                'newsArticleTranslation' => fn ($query) => $query->where('locale', $locale),
            ])
            ->when($keyword !== '', function ($query) use ($keyword, $locale)
            {
                $query->whereHas('newsArticleTranslation', function ($translationQuery) use ($keyword, $locale)
                {
                    $translationQuery
                        ->where('locale', $locale)
                        ->where(function ($keywordQuery) use ($keyword)
                        {
                            $keywordQuery
                                ->where('title', 'like', "%{$keyword}%")
                                ->orWhere('content', 'like', "%{$keyword}%");
                        });
                });
            })
            ->orderByDesc('published_on')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        return response()->json([
            'data' => $articles->map(function (NewsArticle $article) use ($portalBaseUrl)
            {
                $translation = $article->newsArticleTranslation->first();
                $content = trim(strip_tags((string) ($translation?->content ?? '')));

                return [
                    'id' => $article->id,
                    'slug' => $article->slug,
                    'title' => $translation?->title ?? '',
                    'content' => $content,
                    'published_on' => $article->published_on?->toDateString(),
                    'url' => "{$portalBaseUrl}/news/{$article->slug}",
                ];
            }),
            'meta' => [
                'count' => $articles->count(),
                'keyword' => $keyword,
                'locale' => $locale,
                'limit' => $limit,
            ],
        ]);
    }
}
