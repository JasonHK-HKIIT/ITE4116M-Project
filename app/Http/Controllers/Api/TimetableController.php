<?php

namespace App\Http\Controllers\Api;

use App\Enums\CalendarEventType;
use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TimetableController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $user = User::query()
            ->with('student.classes:id')
            ->findOrFail((int) $validated['user_id']);

        $student = $user->student;

        $start = isset($validated['start_date'])
            ? Carbon::createFromFormat('Y-m-d', $validated['start_date'])->startOfDay()
            : now()->startOfMonth()->startOfDay();

        $end = isset($validated['end_date'])
            ? Carbon::createFromFormat('Y-m-d', $validated['end_date'])->endOfDay()
            : now()->addDays(30)->endOfDay();

        if ($end->lt($start))
        {
            throw ValidationException::withMessages([
                'end_date' => 'The end_date must be after or equal to start_date.',
            ]);
        }

        if ($start->diffInDays($end) > 120)
        {
            throw ValidationException::withMessages([
                'end_date' => 'The selected date range cannot exceed 120 days.',
            ]);
        }

        $classIds = $student ? $student->classes()->pluck('classes.id') : collect();

        $events = CalendarEvent::query()
            ->whereBetween('start_at', [$start, $end])
            ->where(function ($query) use ($classIds, $student) {
                if ($student && $classIds->isNotEmpty())
                {
                    $query->orWhereIn('class_id', $classIds);
                }

                if ($student)
                {
                    $query->orWhere(function ($query) use ($student) {
                        $query->where('type', CalendarEventType::ACTIVITY->value)
                            ->where('student_id', $student->id);
                    });
                }

                $query->orWhere('type', CalendarEventType::PUBLIC_HOLIDAY->value);

                if ($student)
                {
                    $query->orWhere(function ($query) use ($student) {
                        $query->where('type', CalendarEventType::INSTITUTE_HOLIDAY->value)
                            ->where(function ($query) use ($student) {
                                $query->where('institute_id', $student->institute_id)
                                    ->orWhereNull('institute_id');
                            });
                        });
                }
            })
            ->orderBy('start_at')
            ->orderBy('id')
            ->get();

        $data = $events->map(function (CalendarEvent $event) {
            return [
                'id' => $event->id,
                'type' => $event->type?->value,
                'source' => $this->mapSource($event->type),
                'title' => $event->title,
                'description' => $event->description,
                'location' => $event->location,
                'instructor' => $event->instructor,
                'start_at' => $event->start_at?->toIso8601String(),
                'end_at' => $event->end_at?->toIso8601String(),
                'class_id' => $event->class_id,
                'student_id' => $event->student_id,
                'institute_id' => $event->institute_id,
            ];
        })->values();

        return response()->json([
            'data' => $data,
            'meta' => [
                'user_id' => $user->id,
                'student_id' => $student?->id,
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'total' => $data->count(),
            ],
        ]);
    }

    private function mapSource(?CalendarEventType $type): string
    {
        return match ($type)
        {
            CalendarEventType::CLASS_TYPE => 'class_timetable',
            CalendarEventType::ACTIVITY => 'student_activity',
            CalendarEventType::INSTITUTE_HOLIDAY => 'institute_holiday',
            CalendarEventType::PUBLIC_HOLIDAY => 'public_holiday',
            default => 'calendar_event',
        };
    }
}
