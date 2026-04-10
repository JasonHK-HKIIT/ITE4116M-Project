<?php

use App\Models\Campus;
use App\Models\ClassModel;
use App\Models\Institute;
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

    public bool $exists = false;

    public ClassModel $class;

    #[Validate('required')]
    public ?int $academic_year = null;

    #[Validate('required')]
    public string $class_code = '';

    public $institute_id = null;

    public $campus_id = null;

    public $programme_id = null;

    public function mount(ClassModel $class): void
    {
        $this->exists = $class->exists;
        $this->class = $class;

        if ($this->exists)
        {
            $this->fill($class->only([
                'academic_year',
                'class_code',
                'institute_id',
                'campus_id',
                'programme_id',
            ]));
        }
    }

    public function render()
    {
        return $this->view()->title(($this->exists ? 'Update' : 'Create') . ' Class');
    }

    #[Computed]
    public function institutes(): Collection
    {
        return Institute::query()
            ->orderByTranslation('name', 'asc')
            ->get();
    }

    #[Computed]
    public function campuses(): Collection
    {
        return Campus::query()
            ->when($this->institute_id, function ($query, $instituteId)
            {
                $query->whereHas('institutes', fn ($query) => $query->where('institutes.id', $instituteId));
            })
            ->when(!$this->institute_id, function ($query)
            {
                $query->whereRaw('1 = 0');
            })
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
        return [
            'academic_year' => ['required', 'integer', 'digits:4'],
            'class_code' => [
                'required',
                'alpha_dash:ascii',
                'max:10',
                Rule::unique('classes', 'class_code')
                    ->where(fn ($query) => $query
                        ->where('academic_year', $this->academic_year)
                        ->where('institute_id', $this->institute_id)
                        ->where('campus_id', $this->campus_id)
                        ->where('programme_id', $this->programme_id)
                    )
                    ->ignore($this->class),
            ],
            'institute_id' => ['required', 'integer', 'exists:institutes,id'],
            'campus_id' => [
                'required',
                'integer',
                Rule::exists('campuses', 'id'),
                Rule::exists('institute_campus', 'campus_id')->where(fn ($query) => $query->where('institute_id', $this->institute_id)),
            ],
            'programme_id' => [
                'required',
                'integer',
                Rule::exists('programmes', 'id')->where(fn ($query) => $query->where('institute_id', $this->institute_id)),
            ],
        ];
    }

    public function updatedInstituteId($value): void
    {
        if (!$value)
        {
            $this->campus_id = null;
            $this->programme_id = null;

            return;
        }

        $campusIds = $this->campuses()->pluck('id')->map(fn ($id) => (int) $id)->all();
        $programmeIds = $this->programmes()->pluck('id')->map(fn ($id) => (int) $id)->all();

        if (!in_array((int) $this->campus_id, $campusIds, true))
        {
            $this->campus_id = null;
        }

        if (!in_array((int) $this->programme_id, $programmeIds, true))
        {
            $this->programme_id = null;
        }
    }

    public function save(): void
    {
        $fields = $this->validate();

        $this->class->fill($fields);
        $this->class->save();

        if ($this->exists)
        {
            $this->success(trans('academic.messages.class_updated'));
        }
        else
        {
            $this->success(
                trans('academic.messages.class_created'),
                redirectTo: route('dashboard.academic.classes.edit', ['class' => $this->class])
            );
        }
    }

    public function with(): array
    {
        return [
            'institutes' => $this->institutes(),
            'campuses' => $this->campuses(),
            'programmes' => $this->programmes(),
        ];
    }
}; ?>

<div>
    <x-header :title="__('academic.classes')" :subtitle="__($exists ? 'academic.modal.update_class' : 'academic.modal.create_class')" separator>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <div class="grid gap-5 md:grid-cols-2">
                <x-input :label="__('academic.form.academic_year')" wire:model="academic_year" type="number" min="1900" max="2100" placeholder="2026" />
                <x-input :label="__('academic.form.class_code')" wire:model="class_code" placeholder="A" />
                <x-select :label="__('academic.form.institute')" wire:model.live="institute_id" :options="$institutes" :placeholder="__('actions.any')" />
                <x-select :label="__('academic.form.campus')" wire:model="campus_id" :options="$campuses" :placeholder="__('actions.any')" />
                <div class="md:col-span-2">
                    <x-select :label="__('academic.form.programme')" wire:model="programme_id" :options="$programmes" :placeholder="__('actions.any')" />
                </div>
            </div>

            <x-slot:actions>
                <x-button :label="__('actions.cancel')" :link="route('dashboard.academic.classes.list')" />
                <x-button :label="__($exists ? 'actions.save' : 'actions.create')" :icon="'fal.' . ($exists ? 'floppy-disk' : 'plus')" type="submit" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
