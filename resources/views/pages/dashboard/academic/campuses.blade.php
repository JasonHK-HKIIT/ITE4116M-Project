<?php

use App\Helpers\LocalesHelper;
use App\Models\Campus;
use App\Models\Institute;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use Toast;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public bool $campusModal = false;

    public string $campusModalSelectedTab = 'en';

    public Campus $campus;

    #[Validate('required')]
    public array $name = [];

    #[Validate([
        'institute_ids' => ['nullable', 'array'],
        'institute_ids.*' => ['required', 'exists:institutes,id']
    ], as: 'institutes')]
    public array $institute_ids = [];

    public Collection $instituteOptions;

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => 'Campus', 'class' => 'w-fit text-nowrap'],
            ['key' => 'institutes', 'label' => 'Institutes', 'sortable' => false, 'class' => 'w-fit'],
        ];
    }

    public function campuses(): Collection
    {
        $sortTranslation = in_array($this->sortBy['column'], ['name']);

        return Campus::query()
            ->when($sortTranslation, function ($query, $value)
            {
                $query->orderByTranslation($this->sortBy['column'], $this->sortBy['direction']);
            })
            ->when(!$sortTranslation, function ($query, $value)
            {
                $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
            })
            ->get();
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'campuses' => $this->campuses(),
            'exists' => $this->campus->exists,
        ];
    }

    public function mount()
    {
        $this->campus = Campus::make();
        $this->instituteOptions = Institute::all();
    }

    public function createCampus(): void
    {
        $this->campus = Campus::make();
        $this->showCampusModel();
    }

    public function updateCampus(int $id): void
    {
        $this->campus = Campus::findOrFail($id);
        $this->showCampusModel();
    }

    public function save(): void
    {
        $isUpdating = $this->campus->exists;

        $fields = LocalesHelper::transformToModelFields($this->validate(), $this->campus->translatedAttributes);
        $this->campus->fill(collect($fields)->except(['institute_ids'])->toArray());
        $this->campus->institutes()->sync($fields['institute_ids']);
        $this->campus->save();

        $this->success('Institute was ' . ($isUpdating ? 'updated' : 'created') . '.');
        $this->campusModal = false;
    }

    protected function rules(): array
    {
        return array_merge(
            LocalesHelper::buildRules('name', ['required', 'max:255']),
        );
    }

    protected function validationAttributes(): array
    {
        return array_merge(
            LocalesHelper::buildValidationAttributes('name'),
        );
    }

    private function showCampusModel(): void
    {
        $this->resetErrorBag();

        $this->name = LocalesHelper::buildPropertyValue();
        $this->institute_ids = [];

        $campus = $this->campus;
        if ($campus->exists)
        {
            $this->fill(LocalesHelper::transformToProperties($campus->getTranslationsArray()));
            $this->institute_ids = $campus->institutes->map(fn($value, $key) => $value->id)->toArray();
        }

        $this->campusModal = true;
    }
}; ?>

<div>
    <x-header :title="__('Institutes')" :subtitle="__('Academic Structure')" separator>
        <x-slot:middle class="!justify-end max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('Search...')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive />
            <x-button icon="fal.plus" class="btn-primary" wire:click="createCampus" spinner responsive />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$campuses" :sort-by="$sortBy">
            @scope('cell_institutes', $campus)
                @foreach ($campus->institutes as $institute)
                    <x-badge :value="$institute->name" class="badge-sm badge-soft text-nowrap" />
                @endforeach
            @endscope
            @scope('actions', $campus)
                <div class="hidden lg:inline-flex flex-row w-8 lg:w-17">
                    <x-button icon="fal.pen-to-square" :tooltip="__('Edit')" wire:click="updateCampus({{ $campus->id }})" spinner class="btn-ghost btn-square btn-sm" />
                    <x-button icon="fal.trash" :tooltip="__('Delete')" wire:click="deleteArticle({{ $campus->id }})" spinner class="btn-ghost btn-square btn-sm" />
                </div>

                <x-dropdown right>
                    <x-slot:trigger>
                        <x-button icon="fal.ellipsis-vertical" class="btn-ghost btn-square btn-sm lg:hidden" />
                    </x-slot:trigger>

                    <x-menu-item title="Edit" icon="fal.pen-to-square" wire:click="updateCampus({{ $campus->id }})" spinner />
                    <x-menu-item title="Delete" icon="fal.trash" wire:click.stop="deleteArticle({{ $campus->id }})" spinner />
                </x-dropdown>
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="campusModal" :title="($exists ? 'Update' : 'Create') . ' Campus'" :subtitle="$campus->name" persistent>
        <x-form wire:submit="save" no-separator>
            <x-tabs wire:model="campusModalSelectedTab" label-div-class="border-b-[length:var(--border)] border-b-base-content/10 flex flex-wrap overflow-x-auto">
                @foreach (LocalesHelper::locales() as $language)
                    <x-tab :name="$language" :label="__('languages.' . $language)" class="pb-0">
                        <x-input label="Name" wire:model="name.{{ $language }}" :placeholder="'Name in ' . __('languages.' . $language)" />
                    </x-tab>
                @endforeach
                <div class="px-1">
                    <x-choices label="Institutes" wire:model="institute_ids" :options="$instituteOptions" />
                </div>
            </x-tabs>

            <x-slot:actions>
                <x-button :label="__('actions.cancel')" @click="$wire.campusModal = false" />
                <x-button :label="__($exists ? 'actions.save' : 'actions.create')" :icon="'fal.' . ($exists ? 'floppy-disk' : 'plus')" type="submit" class="btn-primary" spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>

</div>
