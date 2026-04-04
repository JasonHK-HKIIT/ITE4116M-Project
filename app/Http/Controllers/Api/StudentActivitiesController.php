<?php

namespace App\Http\Controllers\Api;

use App\Enums\Activity\ActivityTypes;
use App\Enums\Activity\Disciplines;
use App\Enums\Language;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentActivitiesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'keyword' => ['nullable', 'string', 'max:120'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:20'],
            'locale' => ['nullable', 'string', Rule::in(Language::values())],
            'activity_type' => ['nullable', 'string', Rule::in(ActivityTypes::values())],
            'campus_id' => ['nullable', 'integer', 'exists:campuses,id'],
            'discipline' => ['nullable', 'string', Rule::in(Disciplines::values())],
        ]);

        $keyword = trim((string) ($validated['keyword'] ?? ''));
        $limit = (int) ($validated['limit'] ?? 5);
        $locale = $validated['locale'] ?? app()->getLocale();
        $today = now()->toDateString();

        $activities = Activity::query()
            ->whereDate('execution_to', '>=', $today)
            ->with([
                'campus.campusTranslation' => fn ($query) => $query->where('locale', $locale),
            ])
            ->when($keyword !== '', function ($query) use ($keyword, $locale)
            {
                $query->whereHas('translations', fn ($query) => $query->whereFullText(['title', 'description'], $keyword));
            })
            ->when(isset($validated['activity_type']), fn ($query) => $query->where('activity_type', $validated['activity_type']))
            ->when(isset($validated['campus_id']), fn ($query) => $query->where('campus_id', $validated['campus_id']))
            ->when(isset($validated['discipline']), fn ($query) => $query->where('discipline', $validated['discipline']))
            ->orderBy('execution_from')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        return response()->json([
            'data' => $activities->map(function (Activity $activity)
            {
                $translation = $activity->activityTranslation->first();
                $campusName = data_get($activity, 'campus.campusTranslation.0.name', '');

                return [
                    'id' => $activity->id,
                    'activity_code' => $activity->activity_code,
                    'activity_type' => $activity->activity_type,
                    'title' => $translation?->title ?? '',
                    'description' => trim(strip_tags((string) ($translation?->description ?? ''))),
                    'venue' => $activity->venue,
                    'venue_remark' => $translation?->venue_remark ?? '',
                    'execution_from' => $activity->execution_from?->toDateString(),
                    'execution_to' => $activity->execution_to?->toDateString(),
                    'campus_id' => $activity->campus_id,
                    'campus_name' => $campusName,
                    'discipline' => $activity->discipline?->value,
                    'registered' => $activity->registered,
                    'capacity' => $activity->capacity,
                    'has_vacancy' => (bool) $activity->has_vacancy,
                ];
            }),
            'meta' => [
                'count' => $activities->count(),
                'keyword' => $keyword,
                'locale' => $locale,
                'limit' => $limit,
                'filters' => [
                    'activity_type' => $validated['activity_type'] ?? null,
                    'campus_id' => $validated['campus_id'] ?? null,
                    'discipline' => $validated['discipline'] ?? null,
                ],
            ],
        ]);
    }
}
