<?php

use App\Enums\Role;
use App\Models\Campus;
use App\Models\ClassModel;
use App\Models\Institute;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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

    public Student $student;

    #[Validate('required')]
    public string $username = '';

    #[Validate('required')]
    public string $family_name = '';

    #[Validate('required')]
    public string $given_name = '';

    public ?string $chinese_name = null;

    public ?string $gender = null;

    public ?string $date_of_birth = null;

    public ?string $nationality = null;

    public ?string $mother_tongue = null;

    public ?string $tel_no = null;

    public ?string $mobile_no = null;

    public ?string $address = null;

    public $institute_id = null;

    public $campus_id = null;

    #[Validate([
        'class_ids' => ['nullable', 'array'],
        'class_ids.*' => ['required', 'integer'],
    ], as: 'classes')]
    public array $class_ids = [];

    public function mount(?User $user = null): void
    {
        $this->exists = (bool) $user?->exists;
        $this->student = new Student();

        if ($this->exists)
        {
            $student = $user?->student;

            abort_if(!$student, 404);

            $this->student = $student;
            $student->load(['user', 'classes']);

            $this->fill([
                'username' => $student->user?->username ?? '',
                'family_name' => $student->user?->family_name ?? '',
                'given_name' => $student->user?->given_name ?? '',
                'chinese_name' => $student->user?->chinese_name,
                'gender' => $student->gender,
                'date_of_birth' => optional($student->date_of_birth)?->format('Y-m-d'),
                'nationality' => $student->nationality,
                'mother_tongue' => $student->mother_tongue,
                'tel_no' => $student->tel_no,
                'mobile_no' => $student->mobile_no,
                'address' => $student->address,
                'institute_id' => $student->institute_id,
                'campus_id' => $student->campus_id,
            ]);

            $this->class_ids = $student->classes()->pluck('classes.id')->toArray();
        }
    }

    public function render()
    {
        return $this->view()->title(($this->exists ? 'Update' : 'Create') . ' Student');
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
            ->orderByTranslation('name', 'asc')
            ->get();
    }

    #[Computed]
    public function classes(): Collection
    {
        return ClassModel::query()
            ->when($this->institute_id, function ($query, $instituteId)
            {
                $query->where('institute_id', $instituteId);
            })
            ->when($this->campus_id, function ($query, $campusId)
            {
                $query->where('campus_id', $campusId);
            })
            ->when(!$this->institute_id || !$this->campus_id, function ($query)
            {
                $query->whereRaw('1 = 0');
            })
            ->orderBy('academic_year', 'desc')
            ->orderBy('class_code', 'asc')
            ->get()
            ->map(function ($class)
            {
                $class->name = $class->class_code . ' (' . $class->academic_year . ')';

                return $class;
            });
    }

    protected function rules(): array
    {
        $userId = $this->student->user?->id;

        return [
            'username' => ['required', 'alpha_dash:ascii', 'max:255', Rule::unique('users', 'username')->ignore($userId)],
            'family_name' => ['required', 'max:255'],
            'given_name' => ['required', 'max:255'],
            'chinese_name' => ['nullable', 'max:255'],
            'gender' => ['nullable', 'max:6'],
            'date_of_birth' => ['nullable', 'date'],
            'nationality' => ['nullable', 'max:255'],
            'mother_tongue' => ['nullable', 'max:255'],
            'tel_no' => ['nullable', 'max:255'],
            'mobile_no' => ['nullable', 'max:255'],
            'address' => ['nullable', 'max:255'],
            'institute_id' => ['required', 'integer', 'exists:institutes,id'],
            'campus_id' => [
                'required',
                'integer',
                Rule::exists('campuses', 'id'),
                Rule::exists('institute_campus', 'campus_id')->where(fn ($query) => $query->where('institute_id', $this->institute_id)),
            ],
            'class_ids.*' => [
                'required',
                'integer',
                Rule::exists('classes', 'id')->where(fn ($query) => $query
                    ->where('institute_id', $this->institute_id)
                    ->where('campus_id', $this->campus_id)
                ),
            ],
        ];
    }

    public function updatedInstituteId($value): void
    {
        if (!$value)
        {
            $this->campus_id = null;
            $this->class_ids = [];

            return;
        }

        $availableCampusIds = $this->campuses()->pluck('id')->map(fn ($id) => (int) $id)->all();

        if (!in_array((int) $this->campus_id, $availableCampusIds, true))
        {
            $this->campus_id = null;
        }

        $this->pruneClassIds();
    }

    public function updatedCampusId(): void
    {
        $this->pruneClassIds();
    }

    protected function pruneClassIds(): void
    {
        if (!$this->institute_id || !$this->campus_id)
        {
            $this->class_ids = [];

            return;
        }

        $allowedClassIds = ClassModel::query()
            ->where('institute_id', $this->institute_id)
            ->where('campus_id', $this->campus_id)
            ->pluck('id')
            ->toArray();

        $this->class_ids = array_values(array_intersect($this->class_ids, $allowedClassIds));
    }

    public function save(): void
    {
        $fields = $this->validate();

        $user = $this->exists ? $this->student->user : new User();
        $user->username = $fields['username'];
        $user->family_name = $fields['family_name'];
        $user->given_name = $fields['given_name'];
        $user->chinese_name = $fields['chinese_name'];

        if (!$this->exists)
        {
            $user->password = Str::random(16);
            $user->role = Role::STUDENT;
        }

        $user->save();

        $this->student->fill(collect($fields)->except([
            'username',
            'family_name',
            'given_name',
            'chinese_name',
            'class_ids',
        ])->toArray());
        $this->student->user()->associate($user);
        $this->student->save();
        $this->student->classes()->sync($fields['class_ids'] ?? []);

        if ($this->exists)
        {
            $this->success(trans('students.messages.updated'));
        }
        else
        {
            $this->success(
                trans('students.messages.created'),
                redirectTo: route('dashboard.students.edit', ['user' => $this->student->user])
            );
        }
    }

    public function with(): array
    {
        return [
            'institutes' => $this->institutes(),
            'campuses' => $this->campuses(),
            'classes' => $this->classes(),
        ];
    }
}; ?>

<div>
    <x-header :title="__('students.title')" :subtitle="__($exists ? 'students.modal.update_student' : 'students.modal.create_student')" separator>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <div class="grid gap-5 md:grid-cols-2">
                <x-input :label="__('students.form.student_id')" wire:model="username" placeholder="240155170" />
                <x-input :label="__('students.form.chinese_name')" wire:model="chinese_name" :placeholder="__('students.form.optional')" />
                <x-input :label="__('students.form.family_name')" wire:model="family_name" />
                <x-input :label="__('students.form.given_name')" wire:model="given_name" />
                <x-select
                    :label="__('students.form.gender')"
                    wire:model="gender"
                    :options="[
                        ['id' => 'Male', 'name' => 'Male'],
                        ['id' => 'Female', 'name' => 'Female'],
                        ['id' => 'Other', 'name' => 'Other'],
                    ]"
                    :placeholder="__('students.form.prefer_not_to_say')"
                />
                <x-datepicker :label="__('students.form.date_of_birth')" wire:model="date_of_birth" clearable />
                <x-input :label="__('students.form.nationality')" wire:model="nationality" />
                <x-input :label="__('students.form.mother_tongue')" wire:model="mother_tongue" />
                <x-input :label="__('students.form.telephone_no')" wire:model="tel_no" />
                <x-input :label="__('students.form.mobile_no')" wire:model="mobile_no" />
                <div class="md:col-span-2">
                    <x-textarea :label="__('students.form.address')" wire:model="address" />
                </div>
                <x-select :label="__('students.filters.institute')" wire:model.live="institute_id" :options="$institutes" :placeholder="__('actions.any')" />
                <x-select :label="__('students.filters.campus')" wire:model.live="campus_id" :options="$campuses" :placeholder="__('actions.any')" />
                <div class="md:col-span-2">
                    <x-choices :label="__('students.form.classes')" wire:model="class_ids" :options="$classes" />
                </div>
            </div>

            <x-slot:actions>
                <x-button :label="__('actions.cancel')" :link="route('dashboard.students.list')" />
                <x-button :label="__($exists ? 'actions.save' : 'actions.create')" :icon="'fal.' . ($exists ? 'floppy-disk' : 'plus')" type="submit" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
