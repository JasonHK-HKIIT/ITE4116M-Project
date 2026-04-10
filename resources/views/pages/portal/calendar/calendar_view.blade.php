<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\CalendarEvent;
use App\Models\Module;
use App\Enums\Role;
use App\Enums\CalendarEventType;
use Illuminate\Support\Str;

new #[Layout('layouts::portal')] class extends Component {

    public string $view = 'month';
    public int $year;
    public int $month;
    public string $weekStart; 
    public ?string $selectedDay = null; 

    public ?int $selectedEventId = null;
    public bool $showEventModal = false;

    public function mount()
    {
        $now = now();
        $this->year = $now->year;
        $this->month = $now->month;
        $this->weekStart = $now->startOfWeek()->format('Y-m-d');
    }

    public function setView($view, $date = null)
    {
        $this->view = $view;
        if ($view === 'week' && $date) {
            $this->weekStart = \Carbon\Carbon::parse($date)->startOfWeek()->format('Y-m-d');
            $this->selectedDay = $date;
        }

        if ($view === 'day') {
            if ($date) {
                $this->selectedDay = $date;
            } elseif (! $this->selectedDay) {
                $this->selectedDay = now()->format('Y-m-d');
            }
        }
    }

    public function prevMonth()
    {
        if ($this->month === 1) {
            $this->month = 12;
            $this->year--;
        } else {
            $this->month--;
        }
    }

    public function nextMonth()
    {
        if ($this->month === 12) {
            $this->month = 1;
            $this->year++;
        } else {
            $this->month++;
        }
    }

    public function prevWeek()
    {
        $start = \Carbon\Carbon::parse($this->weekStart)->subWeek();
        $this->weekStart = $start->format('Y-m-d');
        if ($this->selectedDay) {
            $old = \Carbon\Carbon::parse($this->selectedDay);
            $new = $start->copy()->addDays($old->dayOfWeekIso - 1);
            $this->selectedDay = $new->format('Y-m-d');
        }
    }

    /* For event details x-modal*/
    public function openEvent(int $eventId): void
    {
        $this->selectedEventId = $eventId;
        $this->showEventModal = true;
    }

    public function closeEvent(): void
    {
        $this->showEventModal = false;
    }

    public function nextWeek()
    {
        $start = \Carbon\Carbon::parse($this->weekStart)->addWeek();
        $this->weekStart = $start->format('Y-m-d');
        if ($this->selectedDay) {
            $old = \Carbon\Carbon::parse($this->selectedDay);
            $new = $start->copy()->addDays($old->dayOfWeekIso - 1);
            $this->selectedDay = $new->format('Y-m-d');
        }
    }

    public function prevDay()
    {
        $current = $this->selectedDay ? \Carbon\Carbon::parse($this->selectedDay) : now();
        $this->selectedDay = $current->copy()->subDay()->format('Y-m-d');
        $this->view = 'day';
    }

    public function nextDay()
    {
        $current = $this->selectedDay ? \Carbon\Carbon::parse($this->selectedDay) : now();
        $this->selectedDay = $current->copy()->addDay()->format('Y-m-d');
        $this->view = 'day';
    }

    public function goToday()
    {
        $now = now();
        $this->year = $now->year;
        $this->month = $now->month;
        $this->weekStart = $now->startOfWeek()->format('Y-m-d');
        $this->selectedDay = $now->format('Y-m-d');
    }
}; ?>

@php
    $firstDay = \Carbon\Carbon::create($year, $month, 1);
    $startDayOfWeek = ($firstDay->dayOfWeekIso % 7);
    $weeks = [];
    $day = 1 - $startDayOfWeek;
    for ($w = 0; $w < 6; $w++) {
        $week = [];
        for ($d = 0; $d < 7; $d++, $day++) {
            $currentDate = $firstDay->copy()->addDays($day - 1);
            $isCurrentMonth = ($currentDate->month === $firstDay->month);
            $week[] = [
                'day'   => $currentDate->day,
                'class' => $isCurrentMonth ? '' : 'text-gray-400',
                'date'  => $currentDate->toDateString(),
            ];
        }
        $weeks[] = $week;
    }
    $monthLabel = $firstDay->format('M Y');

    // Prepare events for the current month, filtered by logged-in student's classes (skip for admin)
    $user = auth()->user();
    $isAdmin = $user && $user->role === Role::ADMIN;

    $student = $isAdmin ? null : $user?->student;
    $classIds = $student ? $student->classes()->pluck('classes.id') : collect();

    // Get module names by module_code for current locale
    $locale = app()->getLocale();
    $moduleNamesByCode = Module::all()
        ->mapWithKeys(function ($module) use ($locale) {
            $name = $module->translate($locale)?->name ?? $module->name ?? $module->module_code;
            return [$module->module_code => $name];
        })
        ->toArray();

    $eventTypeClasses = function ($type) {
        return match ($type) {
            CalendarEventType::ACTIVITY => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
            CalendarEventType::PUBLIC_HOLIDAY => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
            CalendarEventType::INSTITUTE_HOLIDAY => 'bg-gray-300 text-gray-800 dark:bg-gray-800 dark:text-gray-100',
            default => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
        };
    };

    $eventLabel = function ($event, $moduleNamesByCode, ?int $limit = 25) {
        $moduleName = $moduleNamesByCode[$event->title] ?? null;
        $label = $event->type === CalendarEventType::CLASS_TYPE
            ? trim($event->title . ' ' . ($moduleName ?? ''))
            : $event->title;

        return $limit ? Str::limit($label, $limit, '...') : $label;
    };

    $getEventsInRange = function ($start, $end) use ($classIds, $isAdmin, $student) {
        // admin only show public holidays in portal calendar.
        if ($isAdmin) {
            return CalendarEvent::query()
                ->whereBetween('start_at', [$start, $end])
                ->where('type', CalendarEventType::PUBLIC_HOLIDAY->value)
                ->get();
        }

        $query = CalendarEvent::query()
            ->whereBetween('start_at', [$start, $end]);

        // for students, always show...:
        // 1.their own class timetable (if they have classes)
        // 2.their own activities (if they have activities)
        // 3.all public & institute holidays
        $query->where(function ($q) use ($classIds, $student) {
            if ($student && $classIds->isNotEmpty()) {
                $q->orWhereIn('class_id', $classIds);
            }

            if ($student) {
                $q->orWhere(function ($q) use ($student) {
                    $q->where('type', CalendarEventType::ACTIVITY->value)
                        ->where('student_id', $student->id);
                });
            }

            // A public holidays show to all students
            $q->orWhere('type', CalendarEventType::PUBLIC_HOLIDAY->value);

            // A institute holidays show only to the student's own institute,
            $q->orWhere(function ($q) use ($student) {
                $q->where('type', CalendarEventType::INSTITUTE_HOLIDAY->value)
                    ->where(function ($q) use ($student) {
                        if ($student && $student->institute_id) {
                            $q->where('institute_id', $student->institute_id)
                                ->orWhereNull('institute_id');
                        } else {
                            $q->whereNull('institute_id');
                        }
                    });
            });
        });

        return $query->get();
    };

    $eventsByDate = [];

    $selectedEvent = null;
    if ($selectedEventId) {
        $selectedEvent = CalendarEvent::with('classModel')->find($selectedEventId);
    }

    $monthStart = $firstDay->copy()->startOfMonth();
    $monthEnd = $firstDay->copy()->endOfMonth();
    $monthEvents = $getEventsInRange($monthStart, $monthEnd);

    foreach ($monthEvents as $event) {
        $dateKey = $event->start_at->toDateString();
        $eventsByDate[$dateKey][] = $event;
    }
@endphp

<div class="p-4 md:p-8 max-w-7xl mx-auto caret-transparent">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-4">
            <h2 class="text-2xl font-bold">Calendar</h2>
            <div class="flex items-center gap-1 px-3 py-1 rounded bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-200 font-semibold text-base shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ now()->format('Y-m-d') }}</span>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <div class="flex gap-2 items-center">
                <!--Month UI (Button)-->
                @if($view==='month')
                <button wire:click="prevMonth" class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">&#60;</button>
                <span class="font-semibold text-lg">{{ $monthLabel }}</span>
                <button wire:click="nextMonth" class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">&#62;</button>
                <!--Week UI (Button)-->
                @elseif($view==='week')
                @php
                    $startOfWeek = \Carbon\Carbon::parse($weekStart);
                    $endOfWeek = $startOfWeek->copy()->addDays(6);
                    $startMonth = $startOfWeek->format('M');
                    $endMonth = $endOfWeek->format('M');
                    $yearLabel = $endOfWeek->format('Y');
                    $weekLabel = $startMonth === $endMonth ? $startMonth . ' ' . $yearLabel : $startMonth . ' - ' . $endMonth . ' ' . $yearLabel;
                @endphp
                <button wire:click="prevWeek" class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">&#60;</button>
                <span class="font-semibold text-lg">{{ $weekLabel }}</span>
                <button wire:click="nextWeek" class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-800 hover:bg-gray-200">&#62;</button>
                <!--Day UI (Button)-->
                @elseif($view==='day')
                    @php
                        $date = $selectedDay ? \Carbon\Carbon::parse($selectedDay) : now();
                    @endphp
                    <button wire:click="prevDay" class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 active:bg-gray-300 focus:ring-2 focus:ring-violet-400 transition">&#60;</button>
                    <span class="font-semibold text-lg">{{ $date->format('d M Y') }}</span>
                    <button wire:click="nextDay" class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 active:bg-gray-300 focus:ring-2 focus:ring-violet-400 transition">&#62;</button>
                @endif
                <button class="bg-violet-300 px-2 py-1 rounded mr-2" wire:click="goToday">Today</button>
            </div>
            <div class="flex gap-2 flex-shrink-0">
                <button wire:click="setView('month')" class="px-3 py-1 rounded font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 hover:bg-blue-500 focus:bg-green-500 @if($view==='month') bg-blue-200 text-blue-700 @endif">Month</button>
                <button wire:click="setView('week')" class="px-3 py-1 rounded font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 hover:bg-blue-500 focus:bg-green-500 @if($view==='week') bg-blue-200 text-blue-700 @endif">Week</button>
                <button wire:click="setView('day')" class="px-3 py-1 rounded font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 hover:bg-blue-500 focus:bg-green-500 @if($view==='day') bg-blue-200 text-blue-700 @endif">Day</button>
            </div>
        </div>
        <!--Month UI (Table)-->
        @if($view==='month')
        <div class="overflow-x-auto">
            <table class="w-full border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-800">
                        <th class="p-2 text-center font-medium text-gray-600 dark:text-gray-300">Mon</th>
                        <th class="p-2 text-center font-medium text-gray-600 dark:text-gray-300">Tue</th>
                        <th class="p-2 text-center font-medium text-gray-600 dark:text-gray-300">Wed</th>
                        <th class="p-2 text-center font-medium text-gray-600 dark:text-gray-300">Thu</th>
                        <th class="p-2 text-center font-medium text-gray-600 dark:text-gray-300">Fri</th>
                        <th class="p-2 text-center font-medium text-gray-600 dark:text-gray-300">Sat</th>
                        <th class="p-2 text-center font-medium text-gray-600 dark:text-gray-300">Sun</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($weeks as $week)
                        <tr>
                            @foreach ($week as $cell)
                                <td class="align-top border border-gray-200 dark:border-gray-700 h-28 w-40 p-2 relative group">
                                    <div class="absolute top-2 left-2 text-xs font-semibold {{ $cell['class'] }}">{{ $cell['day'] }}</div>
                                    @php $dateKey = $cell['date']; @endphp
                                    @if(!empty($eventsByDate[$dateKey]))
                                        <div class="mt-5 space-y-1">
                                            @foreach($eventsByDate[$dateKey] as $event)
                                                <button type="button" wire:click="openEvent({{ $event->id }})" class="w-full text-left px-1 py-0.5 rounded text-xs truncate {{ $eventTypeClasses($event->type) }}">
                                                    @php
                                                        $isAllDay = $event->start_at->isStartOfDay() && $event->end_at->isStartOfDay();
                                                    @endphp
                                                    @unless($isAllDay)
                                                        <div class="text-[10px] leading-tight">
                                                            {{ $event->start_at->format('H:i') }} - {{ $event->end_at->format('H:i') }}
                                                        </div>
                                                    @endunless
                                                    <div class="leading-tight">
                                                        {{ $eventLabel($event, $moduleNamesByCode, 20) }}
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif($view==='week')
        <!--Week UI (Table)-->
        <div class="overflow-x-auto">
            <div class="min-w-[900px] bg-white dark:bg-gray-900 rounded-lg border border-blue-400 p-4">
                <!-- Display the dates and days of the current week -->
                @php
                    $daysOfWeek = [];
                    $tmp = $startOfWeek->copy();
                    for ($i = 0; $i < 7; $i++) {
                        $daysOfWeek[] = [
                            'label' => $tmp->format('D'),
                            'date' => $tmp->format('d'),
                            'isToday' => $tmp->isToday(),
                            'full' => $tmp->format('Y-m-d'),
                        ];
                        $tmp->addDay();
                    }

                    // Prepare events for this week (skip events for admin)
                    $weekEventsByDayHour = [];
                    $weekAllDayEvents = [];

                    $weekStartCarbon = $startOfWeek->copy()->startOfDay();
                    $weekEndCarbon = $startOfWeek->copy()->addDays(6)->endOfDay();

                    $weekEvents = $getEventsInRange($weekStartCarbon, $weekEndCarbon);
                    foreach ($weekEvents as $event) {
                        $startDateKey = $event->start_at->toDateString();

                        if ($event->start_at->isStartOfDay() && $event->end_at->isStartOfDay()) {
                            $weekAllDayEvents[$startDateKey][] = $event;
                            continue;
                        }

                        $startHourKey = (int) $event->start_at->format('H');
                        $weekEventsByDayHour[$startDateKey][$startHourKey][] = $event;
                    }
                @endphp
                <div class="flex border-b border-blue-400 mb-1">
                    <!-- Header time axis spacer -->
                    <div class="w-14"></div>
                    <!-- Header day labels aligned with week grid (auto-highlight today) -->
                    <div class="flex-1 grid grid-cols-7">
                        @foreach($daysOfWeek as $day)
                            <div
                                class="text-center py-1 w-full {{ $day['isToday'] ? 'bg-blue-200 font-bold text-blue-900 rounded-t' : 'font-normal text-gray-700 dark:text-gray-200' }}"
                            >
                                <div class="text-xs">{{ $day['label'] }}</div>
                                <div class="text-base">{{ $day['date'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex">
                    <!-- Time Axis -->
                    <div class="w-14 flex flex-col">
                        <div class="h-8 text-xs text-center text-gray-500">all-day</div>
                        @for ($h = 0; $h < 24; $h++)
                            <div class="h-8 text-xs text-right pr-1 text-gray-400">{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}</div>
                        @endfor
                    </div>
                    <!-- Week Calendar -->
                    <div class="flex-1 grid grid-cols-7 border-l border-gray-200">
                        <!-- all-day row -->
                        @foreach ($daysOfWeek as $dayInfo)
                            @php
                                $dateKey = $dayInfo['full'];
                                $allDayEvents = $weekAllDayEvents[$dateKey] ?? [];
                            @endphp
                            <div class="h-8 border-b border-gray-200 px-1 overflow-hidden">
                                @if(!empty($allDayEvents))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($allDayEvents as $event)
                                            <button type="button" wire:click="openEvent({{ $event->id }})" class="px-1 py-0.5 rounded text-[10px] truncate {{ $eventTypeClasses($event->type) }}">
                                                <span class="block leading-tight">
                                                    {{ $eventLabel($event, $moduleNamesByCode) }}
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        <!-- Hourly Grid Lines -->
                        @for ($h = 0; $h < 24; $h++)
                            @for ($d = 0; $d < 7; $d++)
                                @php
                                    $dayInfo = $daysOfWeek[$d];
                                    $dateKey = $dayInfo['full'];
                                    $slotEvents = $weekEventsByDayHour[$dateKey][$h] ?? [];
                                @endphp
                                <div class="h-8 border-b border-gray-100 relative px-1 overflow-hidden">
                                    @if(!empty($slotEvents))
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($slotEvents as $event)
                                                <button type="button" wire:click="openEvent({{ $event->id }})" class="px-1 py-0.5 rounded text-[10px] truncate {{ $eventTypeClasses($event->type) }}">
                                                    <span class="block text-[9px] leading-tight">
                                                        {{ $event->start_at->format('H:i') }} - {{ $event->end_at->format('H:i') }}
                                                    </span>
                                                    <span class="block leading-tight">
                                                        {{ $eventLabel($event, $moduleNamesByCode) }}
                                                    </span>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endfor
                        @endfor
                    </div>
                </div>
            </div>
        </div>
        <!--Day UI (Table)-->
        @elseif($view==='day')
        <div class="overflow-x-auto">
            <div class="min-w-[600px] bg-white dark:bg-gray-900 rounded-lg border border-blue-400 p-4">
                <div class="flex items-center justify-center mb-2 h-8">
                    <span class="text-lg font-bold text-gray-700 dark:text-gray-200">{{ $date->format('l d') }}</span>
                </div>
                @php
                    $dayStart = $date->copy()->startOfDay();
                    $dayEnd = $date->copy()->endOfDay();
                    $dayEvents = $getEventsInRange($dayStart, $dayEnd);

                    $dayAllDayEvents = [];
                    $dayEventsByHour = [];

                    foreach ($dayEvents as $event) {
                        if ($event->start_at->isStartOfDay() && $event->end_at->isStartOfDay()) {
                            $dayAllDayEvents[] = $event;
                            continue;
                        }

                        $hourKey = (int) $event->start_at->format('H');
                        $dayEventsByHour[$hourKey][] = $event;
                    }
                @endphp
                <div class="flex">
                    <!-- Time Axis -->
                    <div class="w-14 flex flex-col">
                        <div class="h-8 text-xs text-center text-gray-500">all-day</div>
                        @for ($h = 0; $h < 24; $h++)
                            <div class="h-8 text-xs text-right pr-1 text-gray-400">{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}</div>
                        @endfor
                    </div>
                    <!-- Single Day Calendar -->
                    <div class="flex-1 flex flex-col border-l border-gray-200">
                        <div class="h-8 border-b border-gray-200 px-2 flex items-center gap-1 overflow-x-auto">
                            @foreach($dayAllDayEvents as $event)
                                <button type="button" wire:click="openEvent({{ $event->id }})" class="px-2 py-0.5 rounded text-xs whitespace-nowrap truncate {{ $eventTypeClasses($event->type) }}">
                                    <span class="block leading-tight">
                                        {{ $eventLabel($event, $moduleNamesByCode, 30) }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                        @for ($h = 0; $h < 24; $h++)
                            @php
                                $slotEvents = $dayEventsByHour[$h] ?? [];
                            @endphp
                            <div class="h-8 border-b border-gray-100 relative px-2 overflow-hidden">
                                @if(!empty($slotEvents))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($slotEvents as $event)
                                            <button type="button" wire:click="openEvent({{ $event->id }})" class="px-2 py-0.5 rounded text-[11px] truncate {{ $eventTypeClasses($event->type) }}">
                                                <span class="block text-[10px] leading-tight">
                                                    {{ $event->start_at->format('H:i') }} - {{ $event->end_at->format('H:i') }}
                                                </span>
                                                <span class="block leading-tight">
                                                    {{ $eventLabel($event, $moduleNamesByCode, 30) }}
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Calendar click button details-->
    <x-modal
        wire:model="showEventModal"
        :title="__('calendar.labels.details')"
        persistent
    >
        @if($selectedEvent)
            @php
                $isAllDay = $selectedEvent->start_at->isStartOfDay() && $selectedEvent->end_at->isStartOfDay();
            @endphp

            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold uppercase {{ $eventTypeClasses($selectedEvent->type) }}">
                        {{ __('calendar.types.' . $selectedEvent->type->value) }}
                    </span>
                    <div class="font-semibold text-sm">
                        {{ $eventLabel($selectedEvent, $moduleNamesByCode, null) }}
                    </div>
                </div>

                <div class="text-sm text-gray-700 dark:text-gray-200 space-y-1">
                    <div>
                        <span class="font-medium">{{ __('calendar.labels.date') }}:</span>
                        <span>
                            {{ $selectedEvent->start_at->toFormattedDateString() }}
                            @unless($isAllDay)
                                {{ $selectedEvent->start_at->format('H:i') }}
                                -
                                {{ $selectedEvent->end_at->format('H:i') }}
                            @else
                                ({{ __('calendar.labels.whole_day') }})
                            @endunless
                        </span>
                    </div>

                    @if($selectedEvent->location)
                        <div>
                            <span class="font-medium">{{ __('calendar.labels.location') }}:</span>
                            <span>{{ $selectedEvent->location }}</span>
                        </div>
                    @endif

                    @if($selectedEvent->instructor)
                        <div>
                            <span class="font-medium">{{ __('calendar.labels.instructor') }}:</span>
                            <span>{{ $selectedEvent->instructor }}</span>
                        </div>
                    @endif

                    @if($selectedEvent->description)
                        <div class="pt-2 border-t border-gray-200 dark:border-gray-700 text-sm whitespace-pre-line">
                            {{ $selectedEvent->description }}
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <x-slot:actions>
            <x-button
                :label="__('actions.cancel')"
                @click="$wire.closeEvent()"
                class="btn-ghost"
            />
        </x-slot:actions>
    </x-modal>
</div>
