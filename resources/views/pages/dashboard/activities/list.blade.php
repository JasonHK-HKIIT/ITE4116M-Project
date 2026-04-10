<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Activity;
use App\Enums\NewsArticleStatus;
use Livewire\WithPagination;
use Mary\Traits\Toast;
new
#[Layout('layouts::dashboard')]
class extends Component
{
    use WithPagination,Toast;

    public bool $isDrawerOpened = false;

    public int $perPage = 10;

    public string $keywords = '';

    public string $selectedLanguage = 'en';

    public array $sortBy = ['column' => 'title', 'direction' => 'desc'];

    public ?NewsArticleStatus $status = null;

    public ?string $execution_from = null;
    public ?string $execution_to = null;

    public function mount()
    {
        $this->selectedLanguage = app()->getLocale();
    }

    public function changeLanguage($lang)
    {
        app()->setLocale($lang);
        $this->selectedLanguage = $lang;
    }


    public function clear()
    {
        $this->reset([
            'keywords',
            'status',
            'execution_from',
            'execution_to',
        ]);
    }

    public function updatingKeywords()
    {
        $this->resetPage();
    }

    public function updatingExecutionFrom()
    {
        $this->resetPage();
    }

    public function updatingExecutionTo()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function activities()
    {
        return Activity::query()
            ->when($this->keywords, function ($query, $keywords) {
                $query->whereFullText(['title', 'description'], $keywords);
            })
            ->when($this->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($this->execution_from && $this->execution_to, function ($query) {
                $from = $this->execution_from;
                $to   = $this->execution_to;

                $query->where('execution_from', '>=', $from)
                    ->where('execution_to', '<=', $to);
            })
            ->when((($this->sortBy['column'] == 'execution_from') ? $this->sortBy : false), function ($query, $sortBy)
            {
                $query->orderBy('execution_from', ($sortBy['direction'] == 'asc') ? 'desc' : 'asc');
            })
            ->when((($this->sortBy['column'] == 'execution_to') ? $this->sortBy : false), function ($query, $sortBy)
            {
                $query->orderBy('execution_to', ($sortBy['direction'] == 'asc') ? 'desc' : 'asc');
            })
            ->when((($this->sortBy['column'] == 'instructor') ? $this->sortBy : false), function ($query, $sortBy)
            {
                $query->orderBy('instructor', ($sortBy['direction'] == 'asc') ? 'desc' : 'asc');
            })
            ->when($this->sortBy['column'] === 'title', function ($query) {
                $query->orderByTranslation('title', $this->sortBy['direction']);
            })
            ->when((($this->sortBy['column'] == 'total_amount') ? $this->sortBy : false), function ($query, $sortBy)
            {
                $query->orderBy('total_amount', ($sortBy['direction'] == 'asc') ? 'desc' : 'asc');
            })
            ->paginate($this->perPage);
    }

    public function deleteArticle(int $id): void
    {
        if (Activity::destroy($id) > 0) {
            $this->success('Activity was deleted.');
        }
    }


    public function headers(): array
    {
        return [
            ['key' => 'title', 'label' => 'Title','class' => 'w-auto min-w-64'],
            ['key' => 'status', 'label' => 'Status', 'class' => 'w-fit', 'sortable' => false],
            ['key' => 'instructor', 'label' => 'Instructor','class' => 'w-fit'],
            ['key' => 'execution_from', 'label' => 'Execution From', 'class' => 'w-fit', 'format' => ['date', 'd-m-Y']],
            ['key' => 'execution_to', 'label' => 'Execution To','class' => 'w-fit', 'format' => ['date', 'd-m-Y']],
            ['key' => 'total_amount', 'label' => 'Total amount','class' => 'w-fit', 'format' => ['currency', '2,.', '$ ']],
        ];
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'activities' => $this->activities(),
            'statuses' => $this->statuses(),
        ];
    }

    public function statuses(): array
    {
        return collect(NewsArticleStatus::cases())
            ->map(fn($case) => ['id' => $case, 'name' => $case->name])
            ->toArray();
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

        <x-select label="Status" wire:model.live="status" :options="$statuses" placeholder="Any" option-value="id" option-label="name" />

        <x-datepicker label="Execution From" wire:model.live="execution_from" clearable />

        <x-datepicker label="Execution to" wire:model.live="execution_to" clearable />

    </div>
 
    <x-slot:actions>
        <x-button label="Reset" icon="fal.xmark" wire:click="clear" spinner />
        <x-button label="Done" class="btn-primary" @click="$wire.isDrawerOpened = false" />
    </x-slot:actions>


    </x-drawer>

    <x-header :title="__('Activities')" separator>
 
         <x-slot:middle class="!justify-end max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('Search...')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive>
                @if ($status || $execution_from || $execution_to)
                    <x-badge value="1" class="badge-sm badge-primary absolute -top-2 -right-2" />
                @endif
            </x-button>
            <x-button icon="fal.plus" class="btn-primary" :link="route('dashboard.activities.create')" responsive />
        </x-slot:actions>

    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$activities" :sort-by="$sortBy" with-pagination per-page="perPage" :per-page-values="[5, 10, 20]">
            @scope('cell_status', $activity)
                @if ($activity->status->name === 'Draft')
                    <x-badge value="Draft" class="badge-warning" />
                @elseif ($activity->status->name === 'Published')
                    <x-badge value="Published" class="badge-success" />
                @endif
            @endscope

            @scope('actions', $activity)

                <div class="hidden lg:inline-flex flex-row w-8 lg:w-17">
                    <x-button icon="fal.pen-to-square" :tooltip="__('Edit')" :link="route('dashboard.activities.edit', ['activity' => $activity->id])" class="btn-ghost btn-square btn-sm" />
                    <x-button icon="fal.trash" :tooltip="__('Delete')" wire:click="deleteArticle({{ $activity->id }})" spinner class="btn-ghost btn-square btn-sm" />
                </div>        

                    <x-dropdown right>
                        <x-slot:trigger>
                            <x-button icon="fal.ellipsis-vertical" class="btn-ghost btn-square btn-sm lg:hidden" />
                        </x-slot:trigger>

                        <x-menu-item title="Activity Details" icon="fal.pen-to-square" :link="route('dashboard.activities.edit', ['activity' => $activity->id])" />
                        <x-menu-item title="Delete" icon="fal.trash" wire:click.stop="deleteArticle({{ $activity->id }})" spinner />

                    </x-dropdown>
            @endscope
        </x-table>
    </x-card>
</div>
