<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Activity;

new
#[Layout("layouts::portal")]
class extends Component
{

    public bool $isDrawerOpened = false;

    public int $perPage = 10;

    public string $keywords = '';


    //filters
    public ?string $type = null;
    public ?string $execution_from = null;
    public ?string $execution_to = null;
    public ?string $attribute = null;
    public bool $vacancy = false;


    public function clear()
    {
        $this->reset([
            'keywords',
            'execution_from',
            'execution_to'
        ]);
    }

    public function activities()
    {
        return Activity::query()
            ->when($this->keywords, function ($query, $keywords) {
                $query->whereFullText(['title', 'instructor', 'activity_code'], $keywords);
            })
            ->paginate($this->perPage);
    }


    public function headers(): array
    {
        return [
            ['key' => 'title', 'label' => 'Title','class' => 'w-auto min-w-64'],
            ['key' => 'instructor', 'label' => 'Instructor','class' => 'w-fit'],
            ['key' => 'activity_type', 'label' => 'Activity type','class' => 'w-fit'],
            ['key' => 'total_amount', 'label' => 'Total amount','class' => 'w-fit'],
            ['key' => 'has_vacancy', 'label' => 'Vacancy', 'class' => 'w-fit', 'format' => fn($activities, $has_vacancy) => $has_vacancy ? 'Yes' : 'No', 'sortable' => false],
        ];
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'activities' => $this->activities(),
        ];
    }

}; ?>

@assets
    @vite([
        'resources/css/vendor/flatpickr.css',
        'resources/js/vendor/flatpickr.js'
    ])
@endassets

<div>
    <x-drawer
    wire:model="isDrawerOpened"
    title="Filter" 
    separator
    with-close-button
    close-on-escape
    class="w-11/12 lg:w-1/3" right>

    <div>

        <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('Search...')" />

        <x-datepicker label="Execution From" wire:model.live="execution_from" clearable />

        <x-datepicker label="Execution to" wire:model.live="execution_to" clearable />
        
        <x-checkbox label="Activity with Vacancies only" wire:model="vacancy" right />
        
    </div>
 
    <x-slot:actions>
        <x-button label="Reset" icon="fal.xmark" wire:click="clear" spinner />
        <x-button label="Done" icon="fal.check" class="btn-primary" @click="$wire.isDrawerOpened = false" />
    </x-slot:actions>


    </x-drawer>

    <x-header :title="__('Activities')" separator>
 
         <x-slot:middle class="!justify-end max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('Search...')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive />
        </x-slot:actions>

    </x-header>


    <x-card shadow>
        <x-table :headers="$headers" :rows="$activities" with-pagination per-page="perPage" :per-page-values="[5, 10, 20]">
            @scope('actions', $activity)
                <div class="hidden lg:inline-flex flex-row w-8 lg:w-17">
                    <x-button icon="fal.file-lines" :tooltip="__('Activity Details')" :link="route('portal.activities.show', ['id' => $activity->id ])" class="btn-ghost btn-square btn-sm" />
                </div>        

                    <x-dropdown right>
                        <x-slot:trigger>
                            <x-button icon="fal.ellipsis-vertical" class="btn-ghost btn-square btn-sm lg:hidden" />
                        </x-slot:trigger>

                        <x-menu-item title="Activity Details" icon="fal.file-lines" :link="route('portal.activities.show', ['id' => $activity->id ])" />
                    </x-dropdown>
            @endscope
        </x-table>
    </x-card>
</div>
