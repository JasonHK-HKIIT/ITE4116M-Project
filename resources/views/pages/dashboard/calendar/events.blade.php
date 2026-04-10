<?php

use App\Enums\CalendarEventType;
use App\Models\CalendarEvent;
use App\Models\Campus;
use App\Models\ClassModel;
use App\Models\Institute;
use App\Models\Module;
use App\Models\Programme;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mary\Traits\Toast;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use Toast;

    // Add class timetable event
    public array $class_institutes = [];
    public array $class_campuses = [];
    public array $class_programmes = [];
    public array $class_classes = [];
    public array $class_modules = [];

    public ?int $class_institute_id = null;
    public ?int $class_campus_id = null;
    public ?int $class_programme_id = null;
    public ?int $class_class_id = null;
    public ?int $class_module_id = null;

    public string $class_title = '';
    public string $class_location = '';
    public string $class_instructor = '';
    public ?string $class_start_at = null; // datetime-local DD:HH:MM
    public ?string $class_end_time = null; // HH:MM

    public bool $showClassForm = false;

    // Add institute holiday
    public array $institutes = [];

    public ?int $institute_id = null;
    public string $institute_title = '';
    public string $institute_description = '';
    public ?string $institute_date = null;

    public bool $showInstituteForm = false;

    public string $public_title = '';
    public string $public_description = '';
    public ?string $public_date = null;

    public bool $showPublicForm = false;

    public function mount(): void
    {
        $this->institutes = Institute::query()
            ->orderByTranslation('name', 'asc')
            ->get()
            ->map(fn ($institute) => [
                'id' => $institute->id,
                'name' => $institute->name,
            ])
            ->toArray();

        // For class timetable
        $this->class_institutes = $this->institutes;
    }

    public function startCreateClassEvent(): void
    {
        $this->showClassForm = true;
        $this->showInstituteForm = false;
        $this->showPublicForm = false;
    }

    public function updatedClassInstituteId($value): void
    {
        $this->class_campus_id = null;
        $this->class_programme_id = null;
        $this->class_class_id = null;
        $this->class_module_id = null;

        $this->class_campuses = Campus::query()
            ->when($value, function ($query, $instituteId) {
                $query->whereHas('institutes', fn ($q) => $q->where('institutes.id', $instituteId));
            })
            ->orderByTranslation('name', 'asc')
            ->get()
            ->map(fn ($campus) => [
                'id' => $campus->id,
                'name' => $campus->name,
            ])
            ->toArray();

        $this->class_programmes = Programme::query()
            ->when($value, fn ($query, $instituteId) => $query->where('institute_id', $instituteId))
            ->orderBy('programme_code', 'asc')
            ->get()
            ->map(fn ($programme) => [
                'id' => $programme->id,
                'name' => $programme->programme_code,
            ])
            ->toArray();

        $this->class_classes = [];
        $this->class_modules = [];
    }

    public function updatedClassCampusId($value): void
    {
        $this->class_class_id = null;
        $this->loadClassClasses();
    }

    public function updatedClassProgrammeId($value): void
    {
        $this->class_class_id = null;
        $this->loadClassClasses();

        $this->class_modules = Module::query()
            ->whereHas('programmes', fn ($q) => $q->where('programmes.id', $value))
            ->orderBy('module_code')
            ->get()
            ->map(fn ($module) => [
                'id' => $module->id,
                'name' => $module->module_code,
            ])
            ->toArray();
    }

    public function updatedClassModuleId($value): void
    {
        $this->class_title = '';

        if (! $value) {
            return;
        }

        $module = Module::find($value);

        if (! $module) {
            return;
        }

        $code = $module->module_code;
        $name = $module->translate('en')?->name ?? $module->name ?? $code;

        $this->class_title = trim($code . ' - ' . $name);
    }

    protected function loadClassClasses(): void
    {
        if (! $this->class_institute_id || ! $this->class_programme_id) {
            $this->class_classes = [];

            return;
        }

        $this->class_classes = ClassModel::query()
            ->when($this->class_institute_id, fn ($q, $id) => $q->where('institute_id', $id))
            ->when($this->class_campus_id, fn ($q, $id) => $q->where('campus_id', $id))
            ->when($this->class_programme_id, fn ($q, $id) => $q->where('programme_id', $id))
            ->orderByDesc('academic_year')
            ->orderBy('class_code')
            ->get()
            ->map(fn ($class) => [
                'id' => $class->id,
                'label' => $class->academic_year . ' - ' . $class->class_code,
            ])
            ->toArray();
    }

    public function saveClassEvent(): void
    {
        $this->validate([
            'class_institute_id' => ['required', 'integer', 'exists:institutes,id'],
            'class_campus_id' => ['required', 'integer', 'exists:campuses,id'],
            'class_programme_id' => ['required', 'integer', 'exists:programmes,id'],
            'class_class_id' => ['required', 'integer', 'exists:classes,id'],
            'class_module_id' => ['required', 'integer', 'exists:modules,id'],
            'class_title' => ['required', 'string', 'max:255'],
            'class_start_at' => ['required', 'date'],
            'class_end_time' => ['required', 'date_format:H:i'],
            'class_location' => ['nullable', 'string', 'max:255'],
            'class_instructor' => ['nullable', 'string', 'max:255'],
        ]);

        $start = Carbon::parse($this->class_start_at);
        $end = (clone $start)->setTimeFromTimeString($this->class_end_time);

        CalendarEvent::create([
            'class_id'     => $this->class_class_id,
            'student_id'   => null,
            'institute_id' => $this->class_institute_id,
            'type'         => CalendarEventType::CLASS_TYPE,
            'title'        => $this->class_title,
            'description'  => null,
            'location'     => $this->class_location ?: null,
            'instructor'   => $this->class_instructor ?: null,
            'start_at'     => $start,
            'end_at'       => $end,
        ]);

        $this->reset([
            'class_start_at',
            'class_end_time',
        ]);

        $this->resetValidation();

        $this->success(trans('calendar.messages.class_event_created'));
    }

    public function startCreateInstituteHoliday(): void
    {
        $this->showClassForm = false;
        $this->showInstituteForm = true;
        $this->showPublicForm = false;
    }

    public function saveInstituteHoliday(): void
    {
        $this->validate([
            'institute_id' => ['required', 'integer', 'exists:institutes,id'],
            'institute_title' => ['required', 'string', 'max:255'],
            'institute_description' => ['nullable', 'string'],
            'institute_date' => ['required', 'date'],
        ]);

        $date = Carbon::parse($this->institute_date);

        CalendarEvent::create([
            'class_id'     => null,
            'student_id'   => null,
            'institute_id' => $this->institute_id,
            'type'         => CalendarEventType::INSTITUTE_HOLIDAY,
            'title'        => $this->institute_title,
            'description'  => $this->institute_description ?: null,
            'location'     => null,
            'instructor'   => null,
            'start_at'     => $date->copy()->startOfDay(),
            'end_at'       => $date->copy()->endOfDay(),
        ]);

        $this->reset(['institute_title', 'institute_description', 'institute_date']);

        $this->success(trans('calendar.messages.institute_holiday_created'));
    }

    public function startCreatePublicHoliday(): void
    {
        $this->showClassForm = false;
        $this->showPublicForm = true;
        $this->showInstituteForm = false;
    }

    public function savePublicHoliday(): void
    {
        $this->validate([
            'public_title' => ['required', 'string', 'max:255'],
            'public_description' => ['nullable', 'string'],
            'public_date' => ['required', 'date'],
        ]);

        $date = Carbon::parse($this->public_date);

        CalendarEvent::create([
            'class_id'     => null,
            'student_id'   => null,
            'institute_id' => null,
            'type'         => CalendarEventType::PUBLIC_HOLIDAY,
            'title'        => $this->public_title,
            'description'  => $this->public_description ?: null,
            'location'     => null,
            'instructor'   => null,
            'start_at'     => $date->copy()->startOfDay(),
            'end_at'       => $date->copy()->endOfDay(),
        ]);

        $this->reset(['public_title', 'public_description', 'public_date']);

        $this->success(trans('calendar.messages.public_holiday_created'));
    }
}; ?>

<div>
    <x-header :title="__('calendar.events.title')" :subtitle="__('calendar.manage.subtitle')" separator />

    <x-card shadow>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <!-- Class -->
            <button type="button" class="text-left" wire:click="startCreateClassEvent">
                <x-card class="h-full hover:bg-base-200 cursor-pointer">
                    <div class="flex flex-col items-center text-center gap-3 py-6">
                        <x-icon name="fal.table-cells" class="w-10 h-10" />
                        <div class="font-semibold">Class</div>
                        <div class="text-xs text-base-content/70">Add class timetable events</div>
                    </div>
                </x-card>
            </button>

            <!-- Institute Holiday -->
            <button type="button" class="text-left" wire:click="startCreateInstituteHoliday">
                <x-card class="h-full hover:bg-base-200 cursor-pointer">
                    <div class="flex flex-col items-center text-center gap-3 py-6">
                        <x-icon name="fal.building-columns" class="w-10 h-10" />
                        <div class="font-semibold">Institute Holiday</div>
                        <div class="text-xs text-base-content/70">Add institute holidays</div>
                    </div>
                </x-card>
            </button>

            <!-- Public Holiday -->
            <button type="button" class="text-left" wire:click="startCreatePublicHoliday">
                <x-card class="h-full hover:bg-base-200 cursor-pointer">
                    <div class="flex flex-col items-center text-center gap-3 py-6">
                        <x-icon name="fal.earth-asia" class="w-10 h-10" />
                        <div class="font-semibold">Public Holiday</div>
                        <div class="text-xs text-base-content/70">Add public holidays</div>
                    </div>
                </x-card>
            </button>
        </div>
    </x-card>

    @if ($showClassForm)
        <x-card shadow class="mt-6">
            <x-slot:title>
                {{ __('calendar.events.add_class_timetable_event') }}
            </x-slot:title>

            <div class="grid gap-4 md:grid-cols-5">
                <x-select
                    :label="__('calendar.labels.institute')"
                    wire:model.live="class_institute_id"
                    :options="$class_institutes"
                    option-label="name"
                    option-value="id"
                    :placeholder="__('calendar.placeholders.select_institute')"
                />

                <x-select
                    :label="__('calendar.labels.campus')"
                    wire:model.live="class_campus_id"
                    :options="$class_campuses"
                    option-label="name"
                    option-value="id"
                    :placeholder="__('calendar.placeholders.select_campus')"
                    :disabled="! $class_institute_id"
                />

                <x-select
                    :label="__('calendar.labels.programme')"
                    wire:model.live="class_programme_id"
                    :options="$class_programmes"
                    option-label="name"
                    option-value="id"
                    :placeholder="__('calendar.placeholders.select_programme')"
                    :disabled="! $class_institute_id"
                />

                <x-select
                    :label="__('calendar.labels.class')"
                    wire:model.live="class_class_id"
                    :options="$class_classes"
                    option-label="label"
                    option-value="id"
                    :placeholder="__('calendar.placeholders.select_class')"
                    :disabled="! $class_programme_id"
                />

                <x-select
                    :label="__('calendar.labels.module')"
                    wire:model.live="class_module_id"
                    :options="$class_modules"
                    option-label="name"
                    option-value="id"
                    :placeholder="__('calendar.placeholders.select_module')"
                    :disabled="! $class_programme_id"
                />
            </div>

            @if ($class_class_id && $class_module_id)
                <div class="mt-6 grid gap-4 md:grid-cols-5">
                    <x-input
                        :label="__('calendar.labels.title')"
                        value="{{ $class_title }}"
                        :placeholder="__('calendar.placeholders.module_title')"
                        disabled
                        class="md:col-span-2"
                    />

                    <x-input
                        :label="__('calendar.labels.start')"
                        type="datetime-local"
                        wire:model.defer="class_start_at"
                    />

                    <x-input
                        :label="__('calendar.labels.end_time')"
                        type="time"
                        wire:model.defer="class_end_time"
                    />

                    <x-input
                        :label="__('calendar.labels.location')"
                        wire:model.defer="class_location"
                    />

                    <x-input
                        :label="__('calendar.labels.instructor')"
                        wire:model.defer="class_instructor"
                    />
                </div>

                <div class="mt-4 flex justify-end">
                    <x-button wire:click="saveClassEvent" icon="o-plus" class="btn-primary">
                        {{ __('calendar.events.add_class_event') }}
                    </x-button>
                </div>
            @endif
        </x-card>
    @endif

    @if ($showInstituteForm)
        <x-card shadow class="mt-6">
            <x-slot:title>
                {{ __('calendar.events.add_institute_holiday') }}
            </x-slot:title>

            <div class="grid gap-4 md:grid-cols-3">
                <x-select
                    :label="__('calendar.labels.institute')"
                    wire:model.defer="institute_id"
                    :options="$institutes"
                    option-label="name"
                    option-value="id"
                    :placeholder="__('calendar.placeholders.select_institute')"
                />

                <x-input
                    :label="__('calendar.labels.title')"
                    wire:model.defer="institute_title"
                    :placeholder="__('calendar.placeholders.institute_holiday_title')"
                />

                <x-input
                    :label="__('calendar.labels.date')"
                    type="date"
                    wire:model.defer="institute_date"
                />
            </div>

            <div class="mt-4">
                <x-textarea
                    :label="__('calendar.labels.description')"
                    wire:model.defer="institute_description"
                    rows="3"
                    :placeholder="__('calendar.placeholders.description_optional')"
                />
            </div>

            <div class="mt-4 flex justify-end">
                <x-button wire:click="saveInstituteHoliday" icon="o-plus" class="btn-primary">
                    {{ __('calendar.events.add_institute_holiday') }}
                </x-button>
            </div>
        </x-card>
    @endif

    @if ($showPublicForm)
        <x-card shadow class="mt-6">
            <x-slot:title>
                {{ __('calendar.events.add_public_holiday') }}
            </x-slot:title>

            <div class="grid gap-4 md:grid-cols-2">
                <x-input
                    :label="__('calendar.labels.title')"
                    wire:model.defer="public_title"
                    :placeholder="__('calendar.placeholders.public_holiday_title')"
                />

                <x-input
                    :label="__('calendar.labels.date')"
                    type="date"
                    wire:model.defer="public_date"
                />
            </div>

            <div class="mt-4">
                <x-textarea
                    :label="__('calendar.labels.description')"
                    wire:model.defer="public_description"
                    rows="3"
                    :placeholder="__('calendar.placeholders.description_optional')"
                />
            </div>

            <div class="mt-4 flex justify-end">
                <x-button wire:click="savePublicHoliday" icon="o-plus" class="btn-primary">
                    {{ __('calendar.events.add_public_holiday') }}
                </x-button>
            </div>
        </x-card>
    @endif
</div>
