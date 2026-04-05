<?php

use App\Enums\CalendarEventType;
use App\Models\CalendarEvent;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mary\Traits\Toast;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use Toast;

    public string $student_id_input = '';

    public ?int $student_id = null;

    /**
     * Editable data for each activity event, keyed by event id.
     *
     * @var array<int, array<string, mixed>>
     */
    public array $eventsData = [];

    public function searchStudent(): void
    {
        $this->validate([
            'student_id_input' => ['required', 'string'],
        ]);

        $student = Student::query()
            ->whereHas('user', function ($query) {
                $query->where('username', $this->student_id_input);
            })
            ->with(['user', 'institute', 'campus', 'classes.programme'])
            ->first();

        if (! $student) {
            $this->student_id = null;
            $this->eventsData = [];

            $this->error('Student not found.');

            return;
        }

        $this->student_id = $student->id;

        $this->loadEvents();

        $this->success('Student loaded.');
    }

    protected function loadEvents(): void
    {
        $this->eventsData = [];

        if (! $this->student_id) {
            return;
        }

        $events = $this->buildEventsQuery()->get();

        foreach ($events as $event) {
            $this->eventsData[$event->id] = [
                'title' => $event->title,
                'description' => $event->description,
                'location' => $event->location,
                'start_at' => optional($event->start_at)->format('Y-m-d\TH:i'),
                'end_at' => optional($event->end_at)->format('Y-m-d\TH:i'),
            ];
        }
    }

    protected function buildEventsQuery()
    {
        return CalendarEvent::query()
            ->where('student_id', $this->student_id)
            ->where('type', CalendarEventType::ACTIVITY)
            ->orderBy('start_at');
    }

    public function currentEvents()
    {
        if (! $this->student_id) {
            return collect();
        }

        return $this->buildEventsQuery()->get();
    }

    #[Computed]
    public function student(): ?Student
    {
        if (! $this->student_id) {
            return null;
        }

        return Student::with(['user', 'institute', 'campus', 'classes.programme'])
            ->find($this->student_id);
    }

    public function save(): void
    {
        if (! $this->student_id || empty($this->eventsData)) {
            return;
        }

        $this->validate([
            'eventsData.*.title' => ['required', 'string', 'max:255'],
            'eventsData.*.description' => ['nullable', 'string'],
            'eventsData.*.location' => ['nullable', 'string', 'max:255'],
            'eventsData.*.start_at' => ['required', 'date'],
            'eventsData.*.end_at' => ['required', 'date'],
        ]);

        foreach ($this->eventsData as $id => $data) {
            $event = CalendarEvent::query()
                ->where('student_id', $this->student_id)
                ->where('type', CalendarEventType::ACTIVITY)
                ->find($id);

            if (! $event) {
                continue;
            }

            $event->fill([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'location' => $data['location'] ?? null,
                'start_at' => $data['start_at'],
                'end_at' => $data['end_at'],
            ])->save();
        }

        $this->success('Activity events were updated.');

        $this->loadEvents();
    }

    public function with(): array
    {
        return [
            'student' => $this->student(),
            'events' => $this->currentEvents(),
        ];
    }
}; ?>

<div>
    <x-header :title="__('Activities')" :subtitle="__('Calendar')" separator />

    <x-card shadow class="mb-6">
        <x-form wire:submit="searchStudent" class="grid gap-4 md:grid-cols-[2fr_auto] items-end">
            <x-input
                label="Student ID"
                placeholder="Enter student ID (e.g. 240155170)"
                wire:model.defer="student_id_input"
            />

            <x-button type="submit" class="md:w-auto w-full" icon="o-magnifying-glass">
                Search
            </x-button>
        </x-form>
    </x-card>

    @if ($student)
        <x-card shadow class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="font-semibold text-lg">
                        {{ trim(($student->user?->family_name ?? '') . ' ' . ($student->user?->given_name ?? '')) ?: 'Unknown student' }}
                    </div>
                    <div class="text-sm text-base-content/70">
                        ID: {{ $student->user->username ?? '-' }}
                    </div>
                </div>

                <div class="text-sm text-base-content/70 space-y-1 text-right">
                    <div>
                        Institute: {{ $student->institute->name ?? '-' }}
                    </div>
                    <div>
                        Campus: {{ $student->campus->name ?? '-' }}
                    </div>
                    <div>
                        Classes:
                        @php
                            $classes = $student->classes()->with('programme')->get();
                        @endphp
                        @if ($classes->isEmpty())
                            <span>-</span>
                        @else
                            <span>
                                {{ $classes->map(fn ($class) => $class->programme->programme_code . ' ' . $class->class_code)->join(', ') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </x-card>

        <x-card shadow>
            @if ($events->isEmpty())
                <p class="text-sm text-base-content/70">
                    This student has no activity events yet.
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Location</th>
                                <th>Start</th>
                                <th>End</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($events as $event)
                                <tr>
                                    <td class="align-top">
                                        {{ $event->title }}
                                    </td>

                                    <td class="align-top">
                                        <span title="{{ $event->description }}">
                                            {{ \Illuminate\Support\Str::limit($event->description, 80) }}
                                        </span>
                                    </td>

                                    <td class="align-top">
                                        {{ $event->location }}
                                    </td>

                                    <td class="align-top whitespace-nowrap text-sm">
                                        {{ optional($event->start_at)->format('Y-m-d H:i') }}
                                    </td>

                                    <td class="align-top whitespace-nowrap text-sm">
                                        {{ optional($event->end_at)->format('Y-m-d H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>
    @endif
</div>
