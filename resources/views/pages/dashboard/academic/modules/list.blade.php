<?php

use App\Models\Institute;
use App\Models\Module;
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

    public array $sortBy = ['column' => 'module_code', 'direction' => 'asc'];

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
            ['key' => 'module_code', 'label' => 'Module Code', 'class' => 'w-fit min-w-44 text-nowrap'],
            ['key' => 'name', 'label' => 'Module Name', 'class' => 'w-auto min-w-64'],
            ['key' => 'institute', 'label' => 'Institute', 'sortable' => false, 'class' => 'w-fit min-w-48 text-nowrap'],
            ['key' => 'programmes', 'label' => 'Programmes', 'sortable' => false, 'class' => 'w-fit'],
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

    public function modules(): LengthAwarePaginator
    {
        $sortTranslation = in_array($this->sortBy['column'], ['name']);

        return Module::query()
            ->with(['institute', 'programmes'])
            ->when($this->keywords, function ($query, $keywords)
            {
                $query->where(function ($query) use ($keywords)
                {
                    $query
                        ->where('module_code', 'like', "%{$keywords}%")
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

    public function deleteModule(int $id): void
    {
        try
        {
            if (Module::destroy($id) > 0)
            {
                $this->success('Module was deleted.');
            }
        }
        catch (QueryException $exception)
        {
            $this->error('Module cannot be deleted because it is in use.');
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
            'modules' => $this->modules(),
            'institutes' => $this->institutes(),
        ];
    }
}; ?>

<div>
    <x-header :title="__('Modules')" :subtitle="__('Academic Structure')" separator>
        <x-slot:middle class="justify-end! max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('Search...')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive />
            <x-button icon="fal.plus" class="btn-primary" :link="route('dashboard.academic.modules.create')" responsive />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$modules" :sort-by="$sortBy">
            @scope('cell_institute', $module)
                @if ($module->institute)
                    <x-badge :value="$module->institute->name" class="badge-sm badge-soft text-nowrap" />
                @endif
            @endscope

            @scope('cell_programmes', $module)
                @foreach ($module->programmes as $programme)
                    <x-badge :value="$programme->programme_code" class="badge-sm badge-soft text-nowrap" />
                @endforeach
            @endscope

            @scope('actions', $module)
                <div class="hidden lg:inline-flex flex-row w-8 lg:w-17">
                    <x-button icon="fal.pen-to-square" :tooltip="__('Edit')" :link="route('dashboard.academic.modules.edit', ['module' => $module])" class="btn-ghost btn-square btn-sm" />
                    <x-button icon="fal.trash" :tooltip="__('Delete')" wire:click="deleteModule({{ $module->id }})" spinner class="btn-ghost btn-square btn-sm" />
                </div>

                <x-dropdown right>
                    <x-slot:trigger>
                        <x-button icon="fal.ellipsis-vertical" class="btn-ghost btn-square btn-sm lg:hidden" />
                    </x-slot:trigger>

                    <x-menu-item title="Edit" icon="fal.pen-to-square" :link="route('dashboard.academic.modules.edit', ['module' => $module])" />
                    <x-menu-item title="Delete" icon="fal.trash" wire:click.stop="deleteModule({{ $module->id }})" spinner />
                </x-dropdown>
            @endscope
        </x-table>

        <x-pagination :rows="$modules" wire:model.live="perPage" :per-page-values="[5, 10, 25]" />
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
