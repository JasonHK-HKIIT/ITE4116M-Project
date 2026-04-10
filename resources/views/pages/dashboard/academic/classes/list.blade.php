<?php

use App\Models\Campus;
use App\Models\ClassModel;
use App\Models\Institute;
use App\Models\Programme;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use Toast, WithPagination;

    public array $sortBy = ['column' => 'academic_year', 'direction' => 'desc'];

    public int $perPage = 10;

    public bool $isDrawerOpened = false;

    #[Url(as: 'search')]
    public ?string $keywords = null;

    #[Url]
    public $institute_id = null;

    #[Url]
    public $campus_id = null;

    #[Url]
    public $programme_id = null;

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'class_code', 'label' => trans('academic.table.class_code'), 'class' => 'w-fit min-w-36 text-nowrap'],
            ['key' => 'academic_year', 'label' => trans('academic.table.academic_year'), 'class' => 'w-fit min-w-36 text-nowrap'],
            ['key' => 'programme', 'label' => trans('academic.table.programme'), 'class' => 'w-fit min-w-44 text-nowrap', 'sortable' => false],
            ['key' => 'campus', 'label' => trans('academic.table.campus'), 'class' => 'w-fit min-w-44 text-nowrap', 'sortable' => false],
            ['key' => 'institute', 'label' => trans('academic.table.institute'), 'class' => 'w-fit min-w-48 text-nowrap', 'sortable' => false],
            ['key' => 'students_count', 'label' => trans('academic.table.students'), 'class' => 'w-fit min-w-24 text-nowrap'],
        ];
    }

    #[Computed]
    public function institutes(): array
    {
        return Institute::query()
            ->orderByTranslation('name', 'asc')
            ->get()
            ->map(fn ($institute) => ['id' => $institute->id, 'name' => $institute->name])
            ->toArray();
    }

    #[Computed]
    public function campuses(): array
    {
        return Campus::query()
            ->when($this->institute_id, function ($query, $instituteId)
            {
                $query->whereHas('institutes', fn ($query) => $query->where('institutes.id', $instituteId));
            })
            ->orderByTranslation('name', 'asc')
            ->get()
            ->map(fn ($campus) => ['id' => $campus->id, 'name' => $campus->name])
            ->toArray();
    }

    #[Computed]
    public function programmes(): array
    {
        return Programme::query()
            ->when($this->institute_id, function ($query, $instituteId)
            {
                $query->where('institute_id', $instituteId);
            })
            ->orderBy('programme_code', 'asc')
            ->get()
            ->map(fn ($programme) => ['id' => $programme->id, 'name' => $programme->programme_code])
            ->toArray();
    }

    public function classes(): LengthAwarePaginator
    {
        return ClassModel::query()
            ->with(['institute', 'campus', 'programme'])
            ->withCount('students')
            ->when($this->keywords, function ($query, $keywords)
            {
                $query->where(function ($query) use ($keywords)
                {
                    $query
                        ->where('class_code', 'like', "%{$keywords}%")
                        ->orWhere('academic_year', 'like', "%{$keywords}%")
                        ->orWhereHas('programme', fn ($query) => $query->where('programme_code', 'like', "%{$keywords}%"));
                });
            })
            ->when($this->institute_id, function ($query, $instituteId)
            {
                $query->where('institute_id', $instituteId);
            })
            ->when($this->campus_id, function ($query, $campusId)
            {
                $query->where('campus_id', $campusId);
            })
            ->when($this->programme_id, function ($query, $programmeId)
            {
                $query->where('programme_id', $programmeId);
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    public function updatedInstituteId($value): void
    {
        if (!$value)
        {
            $this->campus_id = null;
            $this->programme_id = null;

            return;
        }

        $campusIds = collect($this->campuses())->pluck('id')->all();
        $programmeIds = collect($this->programmes())->pluck('id')->all();

        if (!in_array((int) $this->campus_id, $campusIds, true))
        {
            $this->campus_id = null;
        }

        if (!in_array((int) $this->programme_id, $programmeIds, true))
        {
            $this->programme_id = null;
        }
    }

    public function deleteClass(int $id): void
    {
        try
        {
            if (ClassModel::destroy($id) > 0)
            {
                $this->success(trans('academic.messages.class_deleted'));
            }
        }
        catch (QueryException $exception)
        {
            $this->error(trans('academic.messages.class_delete_in_use'));
        }
    }

    public function clear(): void
    {
        $this->reset([
            'keywords',
            'institute_id',
            'campus_id',
            'programme_id',
        ]);
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'classes' => $this->classes(),
            'institutes' => $this->institutes(),
            'campuses' => $this->campuses(),
            'programmes' => $this->programmes(),
        ];
    }
}; ?>

<div>
    <x-header :title="__('academic.classes')" :subtitle="__('academic.structure')" separator>
        <x-slot:middle class="justify-end! max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('actions.search')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('actions.filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive />
            <x-button icon="fal.plus" class="btn-primary" :link="route('dashboard.academic.classes.create')" responsive />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$classes" :sort-by="$sortBy">
            @scope('cell_programme', $class)
                @if ($class->programme)
                    <x-badge :value="$class->programme->programme_code" class="badge-sm badge-soft text-nowrap" />
                @else
                    <span>-</span>
                @endif
            @endscope

            @scope('cell_campus', $class)
                @if ($class->campus)
                    <x-badge :value="$class->campus->name" class="badge-sm badge-soft text-nowrap" />
                @else
                    <span>-</span>
                @endif
            @endscope

            @scope('cell_institute', $class)
                @if ($class->institute)
                    <x-badge :value="$class->institute->name" class="badge-sm badge-soft text-nowrap" />
                @else
                    <span>-</span>
                @endif
            @endscope

            @scope('cell_students_count', $class)
                {{ $class->students_count }}
            @endscope

            @scope('actions', $class)
                <div class="hidden lg:inline-flex flex-row w-8 lg:w-17">
                    <x-button icon="fal.pen-to-square" :tooltip="__('actions.edit')" :link="route('dashboard.academic.classes.edit', ['class' => $class])" class="btn-ghost btn-square btn-sm" />
                    <x-button icon="fal.trash" :tooltip="__('actions.delete')" wire:click="deleteClass({{ $class->id }})" spinner class="btn-ghost btn-square btn-sm" />
                </div>

                <x-dropdown right>
                    <x-slot:trigger>
                        <x-button icon="fal.ellipsis-vertical" class="btn-ghost btn-square btn-sm lg:hidden" />
                    </x-slot:trigger>

                    <x-menu-item :title="__('actions.edit')" icon="fal.pen-to-square" :link="route('dashboard.academic.classes.edit', ['class' => $class])" />
                    <x-menu-item :title="__('actions.delete')" icon="fal.trash" wire:click.stop="deleteClass({{ $class->id }})" spinner />
                </x-dropdown>
            @endscope
        </x-table>

        <x-pagination :rows="$classes" wire:model.live="perPage" :per-page-values="[5, 10, 25]" />
    </x-card>

    <x-drawer wire:model="isDrawerOpened" :title="__('actions.filters')" right separator with-close-button class="w-3/5 md:w-1/2 lg:w-1/3">
        <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" :placeholder="__('actions.search')" />
        <x-select :label="__('academic.filters.institute')" wire:model.live="institute_id" :options="$institutes" :placeholder="__('actions.any')" />
        <x-select :label="__('academic.filters.campus')" wire:model.live="campus_id" :options="$campuses" :placeholder="__('actions.any')" />
        <x-select :label="__('academic.filters.programme')" wire:model.live="programme_id" :options="$programmes" :placeholder="__('actions.any')" />

        <x-slot:actions>
            <x-button :label="__('actions.reset')" icon="fal.xmark" wire:click="clear" spinner />
            <x-button :label="__('actions.done')" icon="fal.check" class="btn-primary" @click="$wire.isDrawerOpened = false" />
        </x-slot:actions>
    </x-drawer>
</div>
