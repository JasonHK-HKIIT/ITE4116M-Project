<?php

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

    public array $sortBy = ['column' => 'programme_code', 'direction' => 'asc'];

    public int $perPage = 10;

    public bool $isDrawerOpened = false;

    #[Url(as: 'search')]
    public ?string $keywords = null;

    #[Url]
    public $institute_id = null;

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'programme_code', 'label' => 'Programme Code', 'class' => 'w-fit min-w-44 text-nowrap'],
            ['key' => 'name', 'label' => 'Programme Name', 'class' => 'w-auto min-w-64'],
            ['key' => 'institute', 'label' => 'Institute', 'sortable' => false, 'class' => 'w-fit min-w-48 text-nowrap'],
            ['key' => 'modules', 'label' => 'Modules', 'sortable' => false, 'class' => 'w-fit'],
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

    public function programmes(): LengthAwarePaginator
    {
        $sortTranslation = in_array($this->sortBy['column'], ['name']);

        return Programme::query()
            ->with(['institute', 'modules'])
            ->when($this->keywords, function ($query, $keywords)
            {
                $query->where(function ($query) use ($keywords)
                {
                    $query
                        ->where('programme_code', 'like', "%{$keywords}%")
                        ->orWhereHas('translations', fn ($query) => $query->where('name', 'like', "%{$keywords}%"));
                });
            })
            ->when($this->institute_id, function ($query, $instituteId)
            {
                $query->where('institute_id', $instituteId);
            })
            ->when($sortTranslation, function ($query)
            {
                $query->orderByTranslation($this->sortBy['column'], $this->sortBy['direction']);
            })
            ->when(!$sortTranslation, function ($query)
            {
                $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
            })
            ->paginate($this->perPage);
    }

    public function deleteProgramme(int $id): void
    {
        try
        {
            if (Programme::destroy($id) > 0)
            {
                $this->success('Programme was deleted.');
            }
        }
        catch (QueryException $exception)
        {
            $this->error('Programme cannot be deleted because it is in use.');
        }
    }

    public function clear(): void
    {
        $this->reset([
            'keywords',
            'institute_id',
        ]);
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'programmes' => $this->programmes(),
            'institutes' => $this->institutes(),
        ];
    }
}; ?>

<div>
    <x-header :title="__('Programmes')" :subtitle="__('Academic Structure')" separator>
        <x-slot:middle class="justify-end! max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('Search...')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive />
            <x-button icon="fal.plus" class="btn-primary" :link="route('dashboard.academic.programmes.create')" responsive />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$programmes" :sort-by="$sortBy">
            @scope('cell_institute', $programme)
                @if ($programme->institute)
                    <x-badge :value="$programme->institute->name" class="badge-sm badge-soft text-nowrap" />
                @endif
            @endscope

            @scope('cell_modules', $programme)
                @foreach ($programme->modules as $module)
                    <x-badge :value="$module->module_code" class="badge-sm badge-soft text-nowrap" />
                @endforeach
            @endscope

            @scope('actions', $programme)
                <div class="hidden lg:inline-flex flex-row w-8 lg:w-17">
                    <x-button icon="fal.pen-to-square" :tooltip="__('Edit')" :link="route('dashboard.academic.programmes.edit', ['programme' => $programme])" class="btn-ghost btn-square btn-sm" />
                    <x-button icon="fal.trash" :tooltip="__('Delete')" wire:click="deleteProgramme({{ $programme->id }})" spinner class="btn-ghost btn-square btn-sm" />
                </div>

                <x-dropdown right>
                    <x-slot:trigger>
                        <x-button icon="fal.ellipsis-vertical" class="btn-ghost btn-square btn-sm lg:hidden" />
                    </x-slot:trigger>

                    <x-menu-item title="Edit" icon="fal.pen-to-square" :link="route('dashboard.academic.programmes.edit', ['programme' => $programme])" />
                    <x-menu-item title="Delete" icon="fal.trash" wire:click.stop="deleteProgramme({{ $programme->id }})" spinner />
                </x-dropdown>
            @endscope
        </x-table>

        <x-pagination :rows="$programmes" wire:model.live="perPage" :per-page-values="[5, 10, 25]" />
    </x-card>

    <x-drawer wire:model="isDrawerOpened" title="Filters" right separator with-close-button class="w-3/5 md:w-1/2 lg:w-1/3">
        <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" :placeholder="__('Search...')" />
        <x-select label="Institute" wire:model.live="institute_id" :options="$institutes" placeholder="Any" />

        <x-slot:actions>
            <x-button label="Reset" icon="fal.xmark" wire:click="clear" spinner />
            <x-button label="Done" icon="fal.check" class="btn-primary" @click="$wire.isDrawerOpened = false" />
        </x-slot:actions>
    </x-drawer>
</div>
