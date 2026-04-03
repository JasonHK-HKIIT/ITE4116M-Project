<?php

use App\Helpers\LocalesHelper;
use App\Models\Institute;
use App\Models\Module;
use App\Models\Programme;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
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

    public string $selectedLanguage = 'en';

    public bool $exists = false;

    public Module $module;

    #[Validate('required')]
    public array $name = [];

    #[Validate('required')]
    public string $module_code = '';

    public $institute_id = null;

    #[Validate([
        'programme_ids' => ['nullable', 'array'],
        'programme_ids.*' => ['required', 'integer']
    ], as: 'programmes')]
    public array $programme_ids = [];

    public function mount(Module $module): void
    {
        $this->exists = $module->exists;
        $this->module = $module;

        if ($this->exists)
        {
            $this->fill($module->only(['module_code', 'institute_id']));
            $this->fill(LocalesHelper::transformToProperties($module->getTranslationsArray()));
            $this->programme_ids = $module->programmes()->pluck('programmes.id')->toArray();
        }
        else
        {
            $this->name = LocalesHelper::buildPropertyValue();
        }
    }

    public function render()
    {
        return $this->view()->title(($this->exists ? 'Update' : 'Create') . ' Module');
    }

    #[Computed]
    public function institutes(): Collection
    {
        return Institute::query()
            ->orderByTranslation('name', 'asc')
            ->get();
    }

    #[Computed]
    public function programmes(): Collection
    {
        return Programme::query()
            ->when($this->institute_id, function ($query, $instituteId)
            {
                $query->where('institute_id', $instituteId);
            })
            ->when(!$this->institute_id, function ($query)
            {
                $query->whereRaw('1 = 0');
            })
            ->orderBy('programme_code', 'asc')
            ->get();
    }

    protected function rules(): array
    {
        return array_merge(
            [
                'module_code' => [
                    'required',
                    'alpha_dash:ascii',
                    'max:255',
                    Rule::unique('modules', 'module_code')
                        ->where(fn ($query) => $query->where('institute_id', $this->institute_id))
                        ->ignore($this->module),
                ],
                'institute_id' => ['required', 'integer', 'exists:institutes,id'],
                'programme_ids.*' => [
                    'required',
                    'integer',
                    Rule::exists('programmes', 'id')->where(fn ($query) => $query->where('institute_id', $this->institute_id)),
                ],
            ],
            LocalesHelper::buildRules('name', ['required', 'max:255']),
        );
    }

    protected function validationAttributes(): array
    {
        return array_merge(
            LocalesHelper::buildValidationAttributes('name'),
        );
    }

    public function updatedInstituteId($value): void
    {
        if (!$value)
        {
            $this->programme_ids = [];

            return;
        }

        $availableProgrammeIds = Programme::query()
            ->where('institute_id', $value)
            ->pluck('id')
            ->toArray();

        $this->programme_ids = array_values(array_intersect($this->programme_ids, $availableProgrammeIds));
    }

    public function save(): void
    {
        $fields = LocalesHelper::transformToModelFields($this->validate(), $this->module->translatedAttributes);

        $this->module->fill(collect($fields)->except(['programme_ids'])->toArray());
        $this->module->save();
        $this->module->programmes()->sync($fields['programme_ids'] ?? []);

        if ($this->exists)
        {
            $this->success('Module was updated.');
        }
        else
        {
            $this->success(
                'Module was created.',
                redirectTo: route('dashboard.academic.modules.edit', ['module' => $this->module])
            );
        }
    }

    public function with(): array
    {
        return [
            'institutes' => $this->institutes(),
            'programmes' => $this->programmes(),
        ];
    }
}; ?>

<div>
    <x-header :title="__('Modules')" :subtitle="($exists ? 'Update' : 'Create') . ' Module'" separator>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <x-tabs wire:model="selectedLanguage" label-div-class="border-b-[length:var(--border)] border-b-base-content/10 flex flex-wrap overflow-x-auto">
                @foreach (LocalesHelper::locales() as $language)
                    <x-tab :name="$language" :label="__('languages.' . $language)" class="pb-0">
                        <x-input label="Name" wire:model="name.{{ $language }}" :placeholder="'Name in ' . __('languages.' . $language)" />
                    </x-tab>
                @endforeach

                <div class="px-1">
                    <x-input label="Module Code" wire:model="module_code" placeholder="IT114105" />
                    <x-select label="Institute" wire:model.live="institute_id" :options="$institutes" placeholder="Select an institute" />
                    <x-choices label="Programmes" wire:model="programme_ids" :options="$programmes" />
                </div>
            </x-tabs>

            <x-slot:actions>
                <x-button label="Cancel" :link="route('dashboard.academic.modules.list')" />
                <x-button :label="($exists ? 'Save' : 'Create')" :icon="'fal.' . ($exists ? 'floppy-disk' : 'plus')" type="submit" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
