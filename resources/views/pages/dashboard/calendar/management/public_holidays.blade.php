<?php

use App\Enums\CalendarEventType;
use App\Models\CalendarEvent;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mary\Traits\Toast;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use Toast;

    /**
     * Editable data for each holiday event, keyed by event id.
     *
     * @var array<int, array<string, mixed>>
     */
    public array $eventsData = [];

    protected function buildEventsQuery()
    {
        return CalendarEvent::query()
            ->where('type', CalendarEventType::PUBLIC_HOLIDAY)
            ->orderBy('start_at');
    }

    protected function loadEvents(): void
    {
        $this->eventsData = [];

        $events = $this->buildEventsQuery()->get();

        foreach ($events as $event) {
            $this->eventsData[$event->id] = [
                'title' => $event->title,
                'description' => $event->description,
                'date' => $event->start_at->toDateString(),
            ];
        }
    }

    public function mount(): void
    {
        $this->loadEvents();
    }

    public function currentEvents()
    {
        return $this->buildEventsQuery()->get();
    }

    public function save(): void
    {
        if (empty($this->eventsData)) {
            return;
        }

        $this->validate([
            'eventsData.*.title' => ['required', 'string', 'max:255'],
            'eventsData.*.description' => ['nullable', 'string'],
            'eventsData.*.date' => ['required', 'date'],
        ]);

        foreach ($this->eventsData as $id => $data) {
            $event = CalendarEvent::query()
                ->where('type', CalendarEventType::PUBLIC_HOLIDAY)
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

        $this->success('Public holidays were updated.');

        $this->loadEvents();
    }

    public function with(): array
    {
        return [
            'events' => $this->currentEvents(),
        ];
    }
}; ?>

<div>
    <x-header :title="__('Public Holidays')" :subtitle="__('Calendar')" separator />

    <x-card shadow>
        @if ($events->isEmpty())
            <p class="text-sm text-base-content/70">
                There are no public holidays configured yet.
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
</div>
