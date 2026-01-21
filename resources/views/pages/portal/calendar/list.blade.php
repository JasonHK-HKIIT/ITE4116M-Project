
<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new #[Layout('layouts::portal')] class extends Component {
    use Toast, WithPagination;

    public string $view = 'month';
    public int $year;
    public int $month;
    public string $weekStart; 
    public ?string $selectedDay = null; 

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
        if ($view === 'week') {
            if ($date) {
                $this->weekStart = \Carbon\Carbon::parse($date)->startOfWeek()->format('Y-m-d');
                $this->selectedDay = $date;
            } else {
                $today = now();
                $this->weekStart = $today->startOfWeek()->format('Y-m-d');
                $this->selectedDay = $today->format('Y-m-d');
            }
        }
        if ($view === 'day') {
            $this->selectedDay = $date ?? now()->format('Y-m-d');
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
        if ($this->view === 'day') {
            $this->selectedDay = $now->format('Y-m-d');
            $this->year = $now->year;
            $this->month = $now->month;
        } elseif ($this->view === 'week') {
            $this->weekStart = $now->startOfWeek()->format('Y-m-d');
            $this->selectedDay = $now->format('Y-m-d');
            $this->year = $now->year;
            $this->month = $now->month;
        } elseif ($this->view === 'month') {
            $this->year = $now->year;
            $this->month = $now->month;
        }
    }
}; ?>

@php
    $firstDay = \Carbon\Carbon::create($year, $month, 1);
    $daysInMonth = $firstDay->daysInMonth;
    $startDayOfWeek = ($firstDay->dayOfWeekIso % 7); // 0=Sun, 1=Mon...
    $weeks = [];
    $day = 1 - $startDayOfWeek;
    for ($w = 0; $w < 6; $w++) {
        $week = [];
        for ($d = 0; $d < 7; $d++, $day++) {
            if ($day < 1) {
                // last month
                $prevMonth = $firstDay->copy()->subMonth();
                $prevMonthDays = $prevMonth->daysInMonth;
                $week[] = [
                    'day' => $prevMonthDays + $day,
                    'class' => 'text-gray-400',
                ];
            } elseif ($day > $daysInMonth) {
                // next month
                $week[] = [
                    'day' => $day - $daysInMonth,
                    'class' => 'text-gray-400',
                ];
            } else {
                // this month
                $week[] = [
                    'day' => $day,
                    'class' => '',
                ];
            }
        }
        $weeks[] = $week;
    }
    $monthLabel = $firstDay->format('M Y');
    $todayLabel = now()->format('D M Y');
@endphp

<div class="p-4 md:p-8 max-w-7xl mx-auto">
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
        <div class="flex items-center justify-between mb-4">
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
            <div class="flex gap-2">
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
                                    <!-- Event blocks added here(example UI) -->
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
                @endphp
                <div class="grid grid-cols-8 border-b border-blue-400 mb-1">
                    <div class=""></div>
                    @foreach($daysOfWeek as $day)
                        <button type="button"
                            wire:click="setView('week', '{{ $day['full'] }}')"
                            class="text-center py-1 w-full focus:outline-none {{ $selectedDay === $day['full'] ? 'bg-blue-200 font-bold text-blue-900 rounded-t' : ($day['isToday'] ? 'font-bold text-blue-700 rounded-t' : '') }}"
                        >
                            <div class="text-xs">{{ $day['label'] }}</div>
                            <div class="text-base">{{ $day['date'] }}</div>
                        </button>
                    @endforeach
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
                        @for ($d = 0; $d < 7; $d++)
                            <div class="h-8 border-b border-gray-200"></div>
                        @endfor
                        <!-- Hourly Grid Lines -->
                        @for ($h = 0; $h < 24; $h++)
                            @for ($d = 0; $d < 7; $d++)
                                <div class="h-8 border-b border-gray-100 relative"></div>
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
                        <div class="h-8 border-b border-gray-200"></div>
                        @for ($h = 0; $h < 24; $h++)
                            <div class="h-8 border-b border-gray-100 relative"></div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
