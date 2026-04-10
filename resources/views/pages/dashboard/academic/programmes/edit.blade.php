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

    public Programme $programme;

    #[Validate('required')]
    public array $name = [];

    #[Validate('required')]
    public string $programme_code = '';

    public $institute_id = null;

    #[Validate([
        'module_ids' => ['nullable', 'array'],
        'module_ids.*' => ['required', 'integer']
    ], as: 'modules')]
    public array $module_ids = [];

    public function mount(Programme $programme): void
    {
        $this->exists = $programme->exists;
        $this->programme = $programme;

        if ($this->exists)
        {
            $this->fill($programme->only(['programme_code', 'institute_id']));
            $this->fill(LocalesHelper::transformToProperties($programme->getTranslationsArray()));
            $this->module_ids = $programme->modules()->pluck('modules.id')->toArray();
        }
        else
        {
            $this->name = LocalesHelper::buildPropertyValue();
        }
    }

    public function render()
    {
        return $this->view()->title(($this->exists ? 'Update' : 'Create') . ' Programme');
    }

    #[Computed]
    public function institutes(): Collection
    {
        return Institute::query()
            ->orderByTranslation('name', 'asc')
            ->get();
    }

    #[Computed]
    public function modules(): Collection
    {
        return Module::query()
            ->when($this->institute_id, function ($query, $instituteId)
            {
                $query->where('institute_id', $instituteId);
            })
            ->when(!$this->institute_id, function ($query)
            {
                $query->whereRaw('1 = 0');
            })
            ->orderBy('module_code', 'asc')
            ->get();
    }

    protected function rules(): array
    {
        return array_merge(
            [
                'programme_code' => ['required', 'alpha_dash:ascii', 'max:255', Rule::unique('programmes', 'programme_code')->ignore($this->programme)],
                'institute_id' => ['required', 'integer', 'exists:institutes,id'],
                'module_ids.*' => [
                    'required',
                    'integer',
                    Rule::exists('modules', 'id')->where(fn ($query) => $query->where('institute_id', $this->institute_id)),
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
            $this->module_ids = [];

            return;
        }

        $availableModuleIds = Module::query()
            ->where('institute_id', $value)
            ->pluck('id')
            ->toArray();

        $this->module_ids = array_values(array_intersect($this->module_ids, $availableModuleIds));
    }

    public function save(): void
    {
        $fields = LocalesHelper::transformToModelFields($this->validate(), $this->programme->translatedAttributes);

        $this->programme->fill(collect($fields)->except(['module_ids'])->toArray());
        $this->programme->save();
        $this->programme->modules()->sync($fields['module_ids'] ?? []);

        if ($this->exists)
        {
            $this->success(trans('academic.messages.programme_updated'));
        }
        else
        {
            $this->success(
                trans('academic.messages.programme_created'),
                redirectTo: route('dashboard.academic.programmes.edit', ['programme' => $this->programme])
            );
        }
    }

    public function with(): array
    {
        return [
            'institutes' => $this->institutes(),
            'modules' => $this->modules(),
        ];
    }
}; ?>

<div>
    <x-header :title="__('academic.programmes')" :subtitle="__($exists ? 'academic.modal.update_programme' : 'academic.modal.create_programme')" separator>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <x-tabs wire:model="selectedLanguage" label-div-class="border-b-[length:var(--border)] border-b-base-content/10 flex flex-wrap overflow-x-auto">
                @foreach (LocalesHelper::locales() as $language)
                    <x-tab :name="$language" :label="__('languages.' . $language)" class="pb-0">
                        <x-input :label="__('academic.form.name')" wire:model="name.{{ $language }}" :placeholder="trans('academic.form.name_in_language', ['language' => __('languages.' . $language)])" />
                    </x-tab>
                @endforeach

                <div class="px-1">
                    <x-input :label="__('academic.form.programme_code')" wire:model="programme_code" placeholder="IT114105" />
                    <x-select :label="__('academic.form.institute')" wire:model.live="institute_id" :options="$institutes" :placeholder="__('actions.any')" />
                    <x-choices :label="__('academic.form.modules')" wire:model="module_ids" :options="$modules" />
                </div>
            </x-tabs>

            <x-slot:actions>
                <x-button :label="__('actions.cancel')" :link="route('dashboard.academic.programmes.list')" />
                <x-button :label="__($exists ? 'actions.save' : 'actions.create')" :icon="'fal.' . ($exists ? 'floppy-disk' : 'plus')" type="submit" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
