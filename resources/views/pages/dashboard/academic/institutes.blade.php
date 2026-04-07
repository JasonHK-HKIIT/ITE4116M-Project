<?php

use App\Helpers\LocalesHelper;
use App\Models\Campus;
use App\Models\Institute;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use Toast;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public bool $isDrawerOpened = false;

    #[Url(as: 'search')]
    public ?string $keywords = null;

    #[Url]
    public $campus_id = null;

    public bool $instituteModal = false;

    public string $instituteModalSelectedTab = 'en';

    public Institute $institute;

    #[Validate('required')]
    public array $name = [];

    #[Validate([
        'campus_ids' => ['nullable', 'array'],
        'campus_ids.*' => ['required', 'exists:campuses,id']
    ], as: 'campuses')]
    public array $campus_ids = [];

    public Collection $campusOptions;

    #[Computed]
    public function campuses(): array
    {
        return Campus::query()
            ->orderByTranslation('name', 'asc')
            ->get()
            ->map(fn ($campus) => ['id' => $campus->id, 'name' => $campus->name])
            ->toArray();
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => 'Institute', 'class' => 'w-fit min-w-48'],
            ['key' => 'campuses', 'label' => 'Campuses', 'sortable' => false, 'class' => 'w-fit'],
        ];
    }

    public function institutes(): Collection
    {
        $sortTranslation = in_array($this->sortBy['column'], ['name']);

        return Institute::query()
            ->when($this->keywords, function ($query, $keywords)
            {
                $query->whereHas('translations', fn ($query) => $query->where('name', 'like', "%{$keywords}%"));
            })
            ->when($this->campus_id, function ($query, $campusId)
            {
                $query->whereHas('campuses', fn ($query) => $query->where('campuses.id', $campusId));
            })
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
            'institutes' => $this->institutes(),
            'campuses' => $this->campuses(),
            'exists' => $this->institute->exists,
        ];
    }

    public function clear(): void
    {
        $this->reset([
            'keywords',
            'campus_id',
        ]);
    }

    public function mount()
    {
        $this->institute = Institute::make();

        $this->campusOptions = Campus::all();
    }

    public function createInstitute(): void
    {
        $this->institute = Institute::make();
        $this->showInstituteModel();
    }

    public function updateInstitute(int $id): void
    {
        $this->institute = Institute::findOrFail($id);
        $this->showInstituteModel();
    }

    public function save(): void
    {
        $isUpdating = $this->institute->exists;

        $fields = LocalesHelper::transformToModelFields($this->validate(), $this->institute->translatedAttributes);
        $this->institute->fill(collect($fields)->except(['campus_ids'])->toArray());
        $this->institute->save();
        $this->institute->campuses()->sync($fields['campus_ids']);

        $this->success('Institute was ' . ($isUpdating ? 'updated' : 'created') . '.');
        $this->instituteModal = false;
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

    private function showInstituteModel(): void
    {
        $this->resetErrorBag();

        $this->name = LocalesHelper::buildPropertyValue();
        $this->campus_ids = [];

        $institute = $this->institute;
        if ($institute->exists)
        {
            $this->fill(LocalesHelper::transformToProperties($institute->getTranslationsArray()));
            $this->campus_ids = $institute->campuses->map(fn($value, $key) => $value->id)->toArray();
        }

        $this->instituteModal = true;
    }
}; ?>

<div>
    <x-header :title="__('Institutes')" :subtitle="__('Academic Structure')" separator>
        <x-slot:middle class="justify-end! max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('Search...')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive />
            <x-button icon="fal.plus" class="btn-primary" wire:click="createInstitute" spinner responsive />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$institutes" :sort-by="$sortBy">
            @scope('cell_campuses', $institute)
                @foreach ($institute->campuses as $campus)
                    <x-badge :value="$campus->name" class="badge-sm badge-soft text-nowrap" />
                @endforeach
            @endscope
            @scope('actions', $institute)
                <div class="hidden lg:inline-flex flex-row w-8 lg:w-17">
                    <x-button icon="fal.pen-to-square" :tooltip="__('Edit')" wire:click="updateInstitute({{ $institute->id }})" spinner class="btn-ghost btn-square btn-sm" />
                    <x-button icon="fal.trash" :tooltip="__('Delete')" wire:click="deleteArticle({{ $institute->id }})" spinner class="btn-ghost btn-square btn-sm" />
                </div>

                <x-dropdown right>
                    <x-slot:trigger>
                        <x-button icon="fal.ellipsis-vertical" class="btn-ghost btn-square btn-sm lg:hidden" />
                    </x-slot:trigger>

                    <x-menu-item title="Edit" icon="fal.pen-to-square" wire:click="updateInstitute({{ $institute->id }})" spinner />
                    <x-menu-item title="Delete" icon="fal.trash" wire:click.stop="deleteArticle({{ $institute->id }})" spinner />
                </x-dropdown>
            @endscope
        </x-table>
    </x-card>

    <x-drawer wire:model="isDrawerOpened" title="Filters" right separator with-close-button class="w-3/5 md:w-1/2 lg:w-1/3">
        <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" :placeholder="__('Search...')" />
        <x-select label="Campus" wire:model.live="campus_id" :options="$campuses" placeholder="Any" />

        <x-slot:actions>
            <x-button label="Reset" icon="fal.xmark" wire:click="clear" spinner />
            <x-button label="Done" icon="fal.check" class="btn-primary" @click="$wire.isDrawerOpened = false" />
        </x-slot:actions>
    </x-drawer>

    <x-modal wire:model="instituteModal" :title="($exists ? 'Update' : 'Create') . ' Institute'" :subtitle="$institute->name" persistent>
        <x-form wire:submit="save" no-separator>
            <x-tabs wire:model="instituteModalSelectedTab" label-div-class="border-b-[length:var(--border)] border-b-base-content/10 flex flex-wrap overflow-x-auto">
                @foreach (LocalesHelper::locales() as $language)
                    <x-tab :name="$language" :label="__('languages.' . $language)" class="pb-0">
                        <x-input label="Name" wire:model="name.{{ $language }}" :placeholder="'Name in ' . __('languages.' . $language)" />
                    </x-tab>
                @endforeach
                <div class="px-1">
                    <x-choices label="Campuses" wire:model="campus_ids" :options="$campusOptions" />
                </div>
            </x-tabs>

            <x-slot:actions>
                <x-button :label="__('actions.cancel')" @click="$wire.instituteModal = false" />
                <x-button :label="__($exists ? 'actions.save' : 'actions.create')" :icon="'fal.' . ($exists ? 'floppy-disk' : 'plus')" type="submit" class="btn-primary" spinner />
            </x-slot:actions>
        </x-form>
    </x-modal>

</div>
