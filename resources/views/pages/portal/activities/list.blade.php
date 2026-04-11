<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Activity;
use App\Enums\NewsArticleStatus;
use App\Enums\Role;
use App\Models\ActivityRegistration;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
new
#[Layout("layouts::portal")]
class extends Component
{
    use WithPagination;

    public bool $isDrawerOpened = false;

    public int $perPage = 10;

    public string $keywords = '';

    public string $selectedLanguage = 'en';

    public array $sortBy = ['column' => 'title', 'direction' => 'desc'];


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

    public function activities()
    {
        return Activity::query()
            ->where('status', NewsArticleStatus::Published)
            ->when($this->keywords, function ($query, $keywords) {
                $query->whereFullText(['title', 'description'], $keywords);
            })
            ->when($this->execution_from, function ($query) {

                $query->where('execution_from', '>=', $this->execution_from);
            })
            ->when($this->execution_to, function ($query) {
                $query->where('execution_to', '<=', $this->execution_to);
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
            ->when((($this->sortBy['column'] == 'title') ? $this->sortBy : false), function ($query, $sortBy)
            {
                $query->orderByTranslation('title', $this->sortBy['direction']);
            })
            ->when((($this->sortBy['column'] == 'total_amount') ? $this->sortBy : false), function ($query, $sortBy)
            {
                $query->orderBy('total_amount', ($sortBy['direction'] == 'asc') ? 'desc' : 'asc');
            })
            ->paginate($this->perPage);
    }

    public function headers(): array
    {
        return [
            ['key' => 'title', 'label' => trans('activities.table_headers.title'),'class' => 'w-auto min-w-64'],
            ['key' => 'instructor', 'label' => trans('activities.table_headers.instructor'),'class' => 'w-fit'],
            ['key' => 'execution_from', 'label' => trans('activities.table_headers.execution_from'), 'class' => 'w-fit', 'format' => ['date', 'd-m-Y']],
            ['key' => 'execution_to', 'label' => trans('activities.table_headers.execution_to'),'class' => 'w-fit', 'format' => ['date', 'd-m-Y']],
            ['key' => 'total_amount', 'label' => trans('activities.table_headers.total_amount'),'class' => 'w-fit', 'format' => ['currency', '2,.', '$ ']],
        ];
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'activities' => $this->activities(),
        ];
    }

    public function isRegistered($activityId): bool
    {
        if (!Auth::check()) {
            return false;
        }
        $student = Auth::user()->student;
        if (!$student) {
            return false;
        }
        return ActivityRegistration::where('activity_id', $activityId)
            ->where('student_id', $student->id)
            ->exists();
    }

    public function getRegistrationStatus($activityId): ?string
    {
        if (!Auth::check()) {
            return null;
        }
        $student = Auth::user()->student;
        if (!$student) {
            return null;
        }
        return ActivityRegistration::where('activity_id', $activityId)
            ->where('student_id', $student->id)
            ->value('status');
    }

    public function isActivityFull($activity): bool
    {
        return $activity->registered >= $activity->capacity;
    }

    public function isRegistrationOpen($activity): bool
    {
        $now = now();
        return $now->isBetween($activity->execution_from, $activity->execution_to);
    }

    public function isStaffOrAdmin(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        return Auth::user()->hasAnyRole(Role::STAFF, Role::ADMIN);
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
    :title="__('actions.filters')" 
    separator
    with-close-button
    close-on-escape
    class="w-11/12 lg:w-1/3" right>

    <div>

        <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('activities.filters.keywords')" />

        <x-datepicker :label="__('activities.filters.execution_from')" wire:model.live="execution_from" clearable />

        <x-datepicker :label="__('activities.filters.execution_to')" wire:model.live="execution_to" clearable />

    </div>
 
    <x-slot:actions>
        <x-button :label="__('actions.reset')" icon="fal.xmark" wire:click="clear" spinner />
        <x-button :label="__('actions.done')" class="btn-primary" @click="$wire.isDrawerOpened = false" />
    </x-slot:actions>


    </x-drawer>

    <x-header :title="__('activities.title')" separator>
 
         <x-slot:middle class="!justify-end max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('activities.filters.keywords')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('actions.filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive />
        </x-slot:actions>

    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$activities" :sort-by="$sortBy" with-pagination per-page="perPage" :per-page-values="[5, 10, 20]">
            @scope('actions', $activity)
                <div class="hidden lg:inline-flex flex-row w-40 gap-2">
                    <x-button icon="fal.file-lines" :tooltipLeft="__('activities.table_actions.activity_details')" :link="route('portal.activities.show', ['id' => $activity->id ])" class="btn-ghost btn-square btn-sm" />
                </div>        

                    <x-dropdown right>
                        <x-slot:trigger>
                            <x-button icon="fal.ellipsis-vertical" class="btn-ghost btn-square btn-sm lg:hidden" />
                        </x-slot:trigger>

                    <x-menu-item :title="__('activities.table_actions.activity_details')" icon="fal.file-lines" :link="route('portal.activities.show', ['id' => $activity->id ])" />
                    </x-dropdown>
            @endscope
        </x-table>
    </x-card>
</div>
