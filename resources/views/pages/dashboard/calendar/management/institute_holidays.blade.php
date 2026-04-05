<?php

use App\Enums\CalendarEventType;
use App\Models\CalendarEvent;
use App\Models\Institute;
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

    /**
     * Editable data for each holiday event, keyed by event id.
     *
     * @var array<int, array<string, mixed>>
     */
    public array $eventsData = [];

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
    public function selectedInstitute(): ?Institute
    {
        if (! $this->institute_id) {
            return null;
        }

        return Institute::find($this->institute_id);
    }

    public function updatedInstituteId($value): void
    {
        if (! $value) {
            $this->eventsData = [];

            return;
        }

        $this->loadEvents();
    }

    protected function buildEventsQuery()
    {
        return CalendarEvent::query()
            ->where('type', CalendarEventType::INSTITUTE_HOLIDAY)
            ->where('institute_id', $this->institute_id)
            ->orderBy('start_at');
    }

    protected function loadEvents(): void
    {
        $this->eventsData = [];

        if (! $this->institute_id) {
            return;
        }

        $events = $this->buildEventsQuery()->get();

        foreach ($events as $event) {
            $this->eventsData[$event->id] = [
                'title' => $event->title,
                'description' => $event->description,
                'date' => $event->start_at->toDateString(),
            ];
        }
    }

    public function currentEvents()
    {
        if (! $this->institute_id) {
            return collect();
        }

        return $this->buildEventsQuery()->get();
    }

    public function save(): void
    {
        if (! $this->institute_id || empty($this->eventsData)) {
            return;
        }

        $this->validate([
            'eventsData.*.title' => ['required', 'string', 'max:255'],
            'eventsData.*.description' => ['nullable', 'string'],
            'eventsData.*.date' => ['required', 'date'],
        ]);

        foreach ($this->eventsData as $id => $data) {
            $event = CalendarEvent::query()
                ->where('type', CalendarEventType::INSTITUTE_HOLIDAY)
                ->find($id);

            if (! $event) {
                continue;
            }

            $start = Carbon::parse($data['date'])->startOfDay();
            $end = Carbon::parse($data['date'])->endOfDay();

            $event->fill([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'start_at' => $start,
                'end_at' => $end,
            ])->save();
        }

        $this->success('Institute holidays were updated.');

        $this->loadEvents();
    }

    public function with(): array
    {
        return [
            'institutes' => $this->institutes(),
            'selectedInstitute' => $this->selectedInstitute(),
            'events' => $this->currentEvents(),
        ];
    }
}; ?>

<div>
    <x-header :title="__('Institute Holidays')" :subtitle="__('Calendar')" separator />

    <x-card shadow class="mb-6">
        <div class="grid gap-4 md:grid-cols-3 items-end">
            <x-select
                label="Institute"
                wire:model.live="institute_id"
                :options="$institutes"
                option-label="name"
                option-value="id"
                placeholder="Select institute"
            />

            @if ($selectedInstitute)
                <div class="md:col-span-2 text-sm text-base-content/70">
                    <div class="font-semibold text-2xl">{{ $selectedInstitute->name }}</div>
                </div>
            @endif
        </div>
    </x-card>

    @if ($selectedInstitute)
        <x-card shadow>
            @if ($events->isEmpty())
                <p class="text-sm text-base-content/70">
                    There are no institute holidays configured yet.
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($events as $event)
                                <tr>
                                    <td class="align-top">
                                        <x-input
                                            wire:model.defer="eventsData.{{ $event->id }}.title"
                                            placeholder="Title"
                                        />
                                    </td>

                                    <td class="align-top">
                                        <x-textarea
                                            wire:model.defer="eventsData.{{ $event->id }}.description"
                                            rows="2"
                                            placeholder="Description"
                                        />
                                    </td>

                                    <td class="align-top">
                                        <x-input
                                            type="date"
                                            wire:model.defer="eventsData.{{ $event->id }}.date"
                                        />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-end">
                    <x-button wire:click="save" icon="o-check" class="btn-primary">
                        Save changes
                    </x-button>
                </div>
            @endif
        </x-card>
    @endif
</div>
