<?php

use App\Enums\CalendarEventType;
use App\Models\CalendarEvent;
use App\Models\Campus;
use App\Models\ClassModel;
use App\Models\Institute;
use App\Models\Module;
use App\Models\Programme;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mary\Traits\Toast;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use Toast;

    public ?int $institute_id = null;

    public ?int $campus_id = null;

    public ?int $programme_id = null;

    public ?int $class_id = null;

    public ?int $module_id = null;

    // Editable data for each calendar(module,class) event, keyed by event id.
    public array $eventsData = [];

    //IDs of events marked for deletion, applied on save.
    public array $deletedEventIds = [];

    #[Computed]
    public function institutes(): array
    {
        return Institute::query()
            ->orderByTranslation('name', 'asc')
            ->get()
            ->map(fn ($institute) => [
                'id' => $institute->id,
                'name' => $institute->name,
            ])
            ->toArray();
    }

    #[Computed]
    public function campuses(): array
    {
        return Campus::query()
            ->when($this->institute_id, function ($query, $instituteId) {
                $query->whereHas('institutes', fn ($q) => $q->where('institutes.id', $instituteId));
            })
            ->orderByTranslation('name', 'asc')
            ->get()
            ->map(fn ($campus) => [
                'id' => $campus->id,
                'name' => $campus->name,
            ])
            ->toArray();
    }

    #[Computed]
    public function programmes(): array
    {
        return Programme::query()
            ->when($this->institute_id, function ($query, $instituteId) {
                $query->where('institute_id', $instituteId);
            })
            ->orderBy('programme_code', 'asc')
            ->get()
            ->map(fn ($programme) => [
                'id' => $programme->id,
                'name' => $programme->programme_code,
            ])
            ->toArray();
    }

    #[Computed]
    public function modules(): array
    {
        if (! $this->programme_id) {
            return [];
        }

        return Module::query()
            ->whereHas('programmes', fn ($q) => $q->where('programmes.id', $this->programme_id))
            ->orderBy('module_code')
            ->get()
            ->map(fn ($module) => [
                'id' => $module->id,
                'name' => $module->module_code,
            ])
            ->toArray();
    }

    #[Computed]
    public function classes(): array
    {
        return ClassModel::query()
            ->when($this->institute_id, fn ($q, $id) => $q->where('institute_id', $id))
            ->when($this->campus_id, fn ($q, $id) => $q->where('campus_id', $id))
            ->when($this->programme_id, fn ($q, $id) => $q->where('programme_id', $id))
            ->orderByDesc('academic_year')
            ->orderBy('class_code')
            ->get()
            ->map(fn ($class) => [
                'id' => $class->id,
                'label' => $class->academic_year . ' - ' . $class->class_code,
            ])
            ->toArray();
    }

    public function updatedInstituteId($value): void
    {
        if (! $value) {
            $this->campus_id = null;
            $this->programme_id = null;
            $this->class_id = null;
            $this->module_id = null;
            $this->eventsData = [];

            return;
        }

        $this->campus_id = null;
        $this->programme_id = null;
        $this->class_id = null;
        $this->eventsData = [];
    }

    public function updatedCampusId($value): void
    {
        if (! $value) {
            $this->class_id = null;
            $this->module_id = null;
            $this->eventsData = [];

            return;
        }

        $this->class_id = null;
        $this->eventsData = [];
    }

    public function updatedProgrammeId($value): void
    {
        if (! $value) {
            $this->class_id = null;
            $this->module_id = null;
            $this->eventsData = [];

            return;
        }

        $this->class_id = null;
        $this->eventsData = [];
    }

    public function updatedClassId($value): void
    {
        $this->module_id = null;
        $this->loadEvents();
    }

    public function updatedModuleId($value): void
    {
        $this->loadEvents();
    }

    protected function loadEvents(): void
    {
        $this->eventsData = [];
        $this->deletedEventIds = [];

        if (! $this->class_id) {
            return;
        }

        $events = $this->buildEventsQuery()->get();

        foreach ($events as $event) {
            $this->eventsData[$event->id] = [
                'title' => $event->title,
                'location' => $event->location,
                'instructor' => $event->instructor,
                'start_at' => $event->start_at->format('Y-m-d\TH:i'),
                'end_time' => $event->end_at->format('H:i'),
            ];
        }
    }

    public function currentEvents()
    {
        if (! $this->class_id) {
            return collect();
        }

        return $this->buildEventsQuery()->get();
    }

    protected function buildEventsQuery()
    {
        $query = CalendarEvent::query()
            ->where('class_id', $this->class_id)
            ->where('type', CalendarEventType::CLASS_TYPE)
            ->orderBy('start_at');

        if ($this->module_id) {
            $module = Module::find($this->module_id);

            if ($module) {
                $code = $module->module_code;
                $query->where('title', 'like', $code . '%');
            }
        }

        return $query;
    }

    public function save(): void
    {
        if (! $this->class_id || (empty($this->eventsData) && empty($this->deletedEventIds))) {
            return;
        }

        if (! empty($this->eventsData)) {
            $this->validate([
                'eventsData.*.location' => ['nullable', 'string', 'max:255'],
                'eventsData.*.instructor' => ['nullable', 'string', 'max:255'],
                'eventsData.*.start_at' => ['required', 'date'],
                'eventsData.*.end_time' => ['required', 'date_format:H:i'],
            ]);

            foreach ($this->eventsData as $id => $data) {
                $event = CalendarEvent::query()
                    ->where('class_id', $this->class_id)
                    ->where('type', CalendarEventType::CLASS_TYPE)
                    ->find($id);

                if (! $event) {
                    continue;
                }

                $start = Carbon::parse($data['start_at']);
                $end = (clone $start)->setTimeFromTimeString($data['end_time']);

                $event->fill([
                    'location' => $data['location'] ?? null,
                    'instructor' => $data['instructor'] ?? null,
                    'start_at' => $start,
                    'end_at' => $end,
                ])->save();
            }
        }

        if ($this->deletedEventIds) {
            CalendarEvent::query()
                ->where('class_id', $this->class_id)
                ->where('type', CalendarEventType::CLASS_TYPE)
                ->whereIn('id', $this->deletedEventIds)
                ->delete();
        }

        $this->success(trans('calendar.messages.class_timetable_updated'));

        $this->loadEvents();
    }

    public function deleteEvent(int $id): void
    {
        if (! $this->class_id) {
            return;
        }

        if (! in_array($id, $this->deletedEventIds, true)) {
            $this->deletedEventIds[] = $id;
        }

        unset($this->eventsData[$id]);
    }

    public function with(): array
    {
        return [
            'institutes' => $this->institutes(),
            'campuses' => $this->campuses(),
            'programmes' => $this->programmes(),
            'classes' => $this->classes(),
            'modules' => $this->modules(),
            'events' => $this->currentEvents(),
        ];
    }
}; ?>

<div>
    <x-header :title="__('calendar.manage.class_timetable')" :subtitle="__('calendar.manage.subtitle')" separator />

    <x-card shadow>
        <div class="grid gap-4 md:grid-cols-5">
            <x-select
                :label="__('calendar.labels.institute')"
                wire:model.live="institute_id"
                :options="$institutes"
                option-label="name"
                option-value="id"
                :placeholder="__('calendar.placeholders.select_institute')"
            />

            <x-select
                :label="__('calendar.labels.campus')"
                wire:model.live="campus_id"
                :options="$campuses"
                option-label="name"
                option-value="id"
                :placeholder="__('calendar.placeholders.select_campus')"
                :disabled="! $institute_id"
            />

            <x-select
                :label="__('calendar.labels.programme')"
                wire:model.live="programme_id"
                :options="$programmes"
                option-label="name"
                option-value="id"
                :placeholder="__('calendar.placeholders.select_programme')"
                :disabled="! $campus_id"
            />

            <x-select
                :label="__('calendar.labels.class')"
                wire:model.live="class_id"
                :options="$classes"
                option-label="label"
                option-value="id"
                :placeholder="__('calendar.placeholders.select_class')"
                :disabled="! $programme_id"
            />

            <x-select
                :label="__('calendar.labels.module_optional')"
                wire:model.live="module_id"
                :options="$modules"
                option-label="name"
                option-value="id"
                :placeholder="__('calendar.placeholders.any_module')"
                :disabled="! $class_id"
            />
        </div>

        @if($class_id && (! empty($eventsData) && empty($deletedEventIds) ? true : ($class_id && (! empty($eventsData) || ! empty($deletedEventIds)))))
            <form wire:submit.prevent="save" class="mt-8 space-y-4">
                <h2 class="text-lg font-semibold">Timetable for selected class</h2>

                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Location</th>
                                <th>Instructor</th>
                                <th class="w-16 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventsData as $id => $data)
                                <tr wire:key="class-event-{{ $id }}">
                                    <td class="align-middle">
                                        <span class="font-medium">{{ $data['title'] ?? '' }}</span>
                                    </td>
                                    <td>
                                        <x-input
                                            wire:model.defer="eventsData.{{ $id }}.start_at"
                                            type="datetime-local"
                                            class="w-full"
                                        />
                                    </td>
                                    <td>
                                        <x-input
                                            wire:model.defer="eventsData.{{ $id }}.end_time"
                                            type="time"
                                            class="w-full"
                                        />
                                    </td>
                                    <td>
                                        <x-input
                                            wire:model.defer="eventsData.{{ $id }}.location"
                                            type="text"
                                            class="w-full"
                                        />
                                    </td>
                                    <td>
                                        <x-input
                                            wire:model.defer="eventsData.{{ $id }}.instructor"
                                            type="text"
                                            class="w-full"
                                        />
                                    </td>
                                    <td class="text-right align-middle">
                                        <x-button
                                            wire:click="deleteEvent({{ $id }})"
                                            icon="o-trash"
                                            class="btn-ghost btn-xs text-error"
                                        />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-end">
                    <x-button type="submit" class="btn-primary" icon="fal.floppy-disk" :label="__('calendar.actions.save_changes')" spinner="save" />
                </div>
            </form>
        @elseif($class_id)
            <div class="mt-6 text-sm text-base-content/70">
                No timetable events found for the selected class.
            </div>
        @endif
    </x-card>
</div>
