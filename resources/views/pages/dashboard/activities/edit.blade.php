<?php

use App\Helpers\LocalesHelper;
use App\Models\Activity;
use App\Models\Campus;
use App\Models\User;
use App\Enums\Activity\ActivityTypes;
use App\Enums\Activity\Disciplines;
use App\Enums\Activity\Attributes;
use App\Enums\NewsArticleStatus;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\Storage;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use Toast, WithFileUploads;

    public string $selectedLanguage = 'en';

    public bool $exists = false;

    public Activity $activity;

    public string $activity_type = '';

    public string $activity_code = '';

    public int $campus_id = 0;

    #[Validate('required')]
    public array $title = [];

    #[Validate('required')]
    public array $description = [];

    #[Validate('required')]
    public Disciplines $discipline = Disciplines::IT;

    #[Validate('required')]
    public Attributes $attribute = Attributes::EffectiveCommunicators;
     
    public string $instructor = '';

    public string $responsible_staff = '';

    #[Validate('required')]
    public ?Carbon $execution_from = null;

    #[Validate('required')]
    public ?Carbon $execution_to = null;

    public ?Carbon $time_slot_from_date = null;

    public string $time_slot_from_time = '';

    public ?Carbon $time_slot_to_date = null;

    public string $time_slot_to_time = '';

    public float $duration_hours = 0;

    public bool $swpd_programme = false;

    public string $venue = '';

    public array $venue_remark = [];

    public int $capacity = 0;

    public int $registered = 0;

    public float $total_amount = 0;

    public float $included_deposit = 0;

    public NewsArticleStatus $status = NewsArticleStatus::Draft;

    public ActivityTypes $type = ActivityTypes::CampusRepresentatives;

    #[Validate('nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:5120')]
    public $attachment = null;

    public ?string $attachment_display = null;

    #[Computed]
    public function types(): array
    {
        return ActivityTypes::optionsForCurrentLocale();
    }

    public function activityTypes(): array
    {
        return collect(ActivityTypes::cases())->pluck('value')->toArray();
    }

    #[Computed]
    public function disciplines(): array
    {
        return Disciplines::optionsForCurrentLocale();
    }

    #[Computed]
    public function attributes(): array
    {
        return Attributes::optionsForCurrentLocale();
    }

    #[Computed]
    public function statuses(): array
    {
        return collect(NewsArticleStatus::cases())
            ->map(fn ($case) => ['id' => $case, 'name' => trans('activities.statuses.' . strtolower($case->name))])
            ->toArray();
    }

    #[Computed]
    public function campuses(): array
    {
        $locale = app()->getLocale();
        return Campus::all()->map(fn($campus) => [
            'value' => $campus->id,
            'label' => $campus->translate($locale)->name ?? $campus->translate('en')->name ?? 'Campus ' . $campus->id
        ])->toArray();
    }

    #[Computed]
    public function instructors(): array
    {
        return [
            ['id' => 'Tom', 'name' => 'Tom Wilson'],
            ['id' => 'Sarah', 'name' => 'Sarah Johnson'],
            ['id' => 'Michael', 'name' => 'Michael Chen'],
            ['id' => 'Emma', 'name' => 'Emma Davis'],
            ['id' => 'James', 'name' => 'James Brown'],
            ['id' => 'Lisa', 'name' => 'Lisa Anderson'],
            ['id' => 'David', 'name' => 'David Martinez'],
            ['id' => 'Jennifer', 'name' => 'Jennifer Lee'],
        ];
    }

    #[Computed]
    public function staff(): array
    {
        return [
            ['id' => 'Joe', 'name' => 'Joe Thompson'],
            ['id' => 'Maria', 'name' => 'Maria Garcia'],
            ['id' => 'Robert', 'name' => 'Robert White'],
            ['id' => 'Anna', 'name' => 'Anna Taylor'],
            ['id' => 'Kevin', 'name' => 'Kevin Harris'],
            ['id' => 'Nicole', 'name' => 'Nicole Clark'],
            ['id' => 'Thomas', 'name' => 'Thomas Lewis'],
            ['id' => 'Patricia', 'name' => 'Patricia Robinson'],
        ];
    }

    public function config1(): array
    {
        return [
           'dateFormat' => 'YYYY-MM-DD HH:MM:SS'
        ];
    }


    protected function rules(): array
    {
        $primaryLocale = app()->getLocale();

        return array_merge(
            [
                'activity_type' => ['nullable', 'string', Rule::in($this->activityTypes())],
                'activity_code' => ['nullable', 'string', 'max:255', Rule::unique('activities', 'activity_code')->ignore($this->activity?->id)],
                'status' => ['required', Rule::enum(NewsArticleStatus::class)],
                'attribute'=> ['required', Rule::enum(Attributes::class)],
                'discipline'=> ['nullable', Rule::enum(Disciplines::class)],
                'campus_id' => ['required', 'integer', 'exists:campuses,id'],
                'instructor' => ['nullable', 'string', 'max:255'],
                'responsible_staff' => ['nullable', 'string', 'max:255'],
                'execution_from' => ['required', 'date'],
                'execution_to' => ['required', 'date', 'after_or_equal:execution_from'],
                'time_slot_from_date' => ['nullable', 'date'],
                'time_slot_from_time' => ['nullable', 'date_format:H:i'],
                'time_slot_to_date' => ['nullable', 'date'],
                'time_slot_to_time' => ['nullable', 'date_format:H:i'],
                'duration_hours' => ['nullable', 'numeric', 'min:0'],
                'swpd_programme' => ['boolean'],
                'venue' => ['nullable', 'string', 'max:255'],
                "venue_remark.{$primaryLocale}" => ['nullable', 'string'],
                'capacity' => ['required', 'integer', 'min:0'],
                'registered' => ['required', 'integer', 'min:0', 'max:' . (int)$this->capacity],
                'total_amount' => ['required', 'numeric', 'min:0'],
                'included_deposit' => ['required', 'numeric', 'min:0', 'max:' . (int)$this->total_amount],
                "title.{$primaryLocale}" => ['required', 'max:255'],
                "description.{$primaryLocale}" => ['required', 'max:16777215'],
            ],
        );
    }

    protected function validationAttributes(): array
    {
        $primaryLocale = app()->getLocale();
        $localeName = __("languages.{$primaryLocale}");

        return [
            'activity_code' => trans('activities.form.activity_code'),
            'campus_id' => trans('activities.form.campus'),
            'status' => trans('activities.form.status'),
            'time_slot_from_date' => trans('activities.form.time_slot_from_date'),
            'time_slot_from_time' => trans('activities.form.time_slot_from_time'),
            'time_slot_to_date' => trans('activities.form.time_slot_to_date'),
            'time_slot_to_time' => trans('activities.form.time_slot_to_time'),
            'activity_type' => trans('activities.form.activity_type'),
            'discipline' => trans('activities.form.discipline'),
            'attribute' => trans('activities.form.attribute'),
            "title.{$primaryLocale}" => trans('activities.form.title') . " ({$localeName})",
            "description.{$primaryLocale}" => trans('activities.form.description') . " ({$localeName})",
            "venue_remark.{$primaryLocale}" => trans('activities.form.venue_remark') . " ({$localeName})",
        ];
    }

    public function mount(?Activity $activity = null)
    {
        if ($activity === null) {
            $activity = new Activity();
        }

        $this->exists = $activity->exists;
        $this->activity = $activity;

        if ($this->exists)
        {
            $this->fill($activity->only([
                'activity_type', 'activity_code', 'campus_id', 'instructor',
                'responsible_staff', 'execution_from', 'execution_to', 'time_slot_from_date',
                'time_slot_to_date', 'duration_hours', 'swpd_programme', 'venue',
                'capacity', 'registered', 'total_amount',
                'included_deposit', 'discipline', 'attribute', 'status'
            ]));
            if ($activity->time_slot_from_time) {
                $this->time_slot_from_time = $activity->time_slot_from_time->format('H:i');
            }
            if ($activity->time_slot_to_time) {
                $this->time_slot_to_time = $activity->time_slot_to_time->format('H:i');
            }
            $this->fill(LocalesHelper::transformToProperties($activity->getTranslationsArray()));

            // Load existing attachment
            if ($activity->attachment) {
                $this->attachment_display = $activity->attachment;
            }
        }
        else
        {
            $this->title = LocalesHelper::buildPropertyValue();
            $this->description = LocalesHelper::buildPropertyValue();
            $this->venue_remark = LocalesHelper::buildPropertyValue();
            $this->instructor = 'Tom';
            
            // Set default campus
            $firstCampus = Campus::first();
            if ($firstCampus) {
                $this->campus_id = $firstCampus->id;
            }
            
            // Set default dates
            $this->execution_from = Carbon::today();
            $this->execution_to = Carbon::today()->addWeek();
            $this->time_slot_from_date = $this->execution_to->clone()->addDay();
            $this->time_slot_from_time = '09:00';
            $this->time_slot_to_date = $this->execution_to->clone()->addDay();
            $this->time_slot_to_time = '10:00';
            
            // Set default numeric values
            $this->capacity = 50;
            $this->registered = 0;
            $this->total_amount = 0;
            $this->included_deposit = 0;
            $this->duration_hours = 1;
        }
    }

    public function render()
    {
        return $this->view()->title(trans($this->exists ? 'activities.subtitles.update_activity' : 'activities.subtitles.create_activity'));
    }

    private function getActivityUploadPath(): string
    {
        if ($this->activity->exists && $this->activity->id) {
            return "activities/{$this->activity->id}";
        }
        return "activities/temp";
    }

    public function uploadAttachment(): void
    {
        $this->validate(['attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:5120']);

        if ($this->attachment) {
            try {
                $uploadPath = $this->getActivityUploadPath();

                // Delete old attachment if exists
                if ($this->attachment_display && Storage::disk('public')->exists("{$uploadPath}/{$this->attachment_display}")) {
                    Storage::disk('public')->delete("{$uploadPath}/{$this->attachment_display}");
                }

                // Ensure directory exists
                if (!Storage::disk('public')->exists($uploadPath)) {
                    Storage::disk('public')->makeDirectory($uploadPath);
                }

                // Store the new file
                $filename = $this->attachment->getClientOriginalName();
                $path = $this->attachment->storeAs($uploadPath, $filename, 'public');
                $this->attachment_display = $filename;
                $this->attachment = null; // Clear the upload input
                
                $this->success(__('activities.messages.file_uploaded', ['filename' => $filename]));
            } catch (\Exception $e) {
                $this->error('File upload failed: ' . $e->getMessage());
            }
        }
    }

    public function updatedAttachment(): void
    {
        $this->uploadAttachment();
    }

    public function deleteAttachment(): void
    {
        if ($this->attachment_display) {
            $uploadPath = $this->getActivityUploadPath();
            if (Storage::disk('public')->exists("{$uploadPath}/{$this->attachment_display}")) {
                Storage::disk('public')->delete("{$uploadPath}/{$this->attachment_display}");
            }
            $this->attachment_display = null;
            $this->attachment = null;
            $this->success(__('activities.messages.file_deleted'));
        }
    }

    public function getAttachmentUrl(): ?string
    {
        if ($this->attachment_display) {
            $uploadPath = $this->getActivityUploadPath();
            if (Storage::disk('public')->exists("{$uploadPath}/{$this->attachment_display}")) {
                return Storage::disk('public')->url("{$uploadPath}/{$this->attachment_display}");
            }
        }
        return null;
    }

    public function save(): void
    {
        if (empty($this->published_on) && ($this->status == NewsArticleStatus::Published)) { $this->published_on = Carbon::today(); }
            $validated = $this->validate();

            $fields = LocalesHelper::transformToModelFields($validated, $this->activity->translatedAttributes);
            
            // Handle attachment - move temp files to activity folder if newly created
            if ($this->attachment_display) {
                $fields['attachment'] = $this->attachment_display;
                
                // If this is a new activity, move temp files to activity-specific folder
                if (!$this->exists) {
                    $oldPath = "activities/temp/{$this->attachment_display}";
                    $newPath = "activities/{$this->activity->id}/{$this->attachment_display}";
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->makeDirectory("activities/{$this->activity->id}");
                        Storage::disk('public')->move($oldPath, $newPath);
                    }
                }
            } elseif ($this->exists && !$this->attachment_display) {
                // If we're editing and attachment was deleted
                if ($this->activity->attachment && Storage::disk('public')->exists("activities/{$this->activity->id}/{$this->activity->attachment}")) {
                    Storage::disk('public')->delete("activities/{$this->activity->id}/{$this->activity->attachment}");
                }
                $fields['attachment'] = null;
            }

            $this->activity->fill($fields);
            $this->activity->save();

            if ($this->exists)
            {
                $this->success(trans('activities.messages.updated'));
            }
            else
            {
                $this->success(
                    trans('activities.messages.created'),
                    redirectTo: route('dashboard.activities.list'));
            }
    }

    public function calculateDuration(): void
    {
        if ($this->time_slot_from_date && $this->time_slot_from_time && $this->time_slot_to_date && $this->time_slot_to_time) {
            $from = Carbon::parse($this->time_slot_from_date->format('Y-m-d') . ' ' . $this->time_slot_from_time);
            $to = Carbon::parse($this->time_slot_to_date->format('Y-m-d') . ' ' . $this->time_slot_to_time);
            
            if ($to->greaterThan($from)) {
                $this->duration_hours = round($from->diffInMinutes($to) / 60, 2);
            }
        }
    }

    public function updatedTimeSlotFromDate($value): void
    {
        $this->calculateDuration();
    }

    public function updatedTimeSlotFromTime($value): void
    {
        $this->calculateDuration();
    }

    public function updatedTimeSlotToDate($value): void
    {
        $this->calculateDuration();
    }

    public function updatedTimeSlotToTime($value): void
    {
        $this->calculateDuration();
    }

    public function with(): array
    {
        return [
            'campuses' => $this->campuses(),
            'types' => $this->types(),
            'disciplines' => $this->disciplines(),
            'attributes' => $this->attributes(),
            'statuses' => $this->statuses(),
            'instructors' => $this->instructors(),
            'staff' => $this->staff(),
        ];
    }
}; ?>

@assets
    @vite([
        'resources/css/vendor/flatpickr.css',
        'resources/js/vendor/flatpickr.js',
        'resources/js/vendor/tinymce.js'
    ])
@endassets

<div>
    <x-header :title="__('activities.title')" :subtitle="__($exists ? 'activities.subtitles.update_activity' : 'activities.subtitles.create_activity')" separator>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <x-tabs wire:model="selectedLanguage" label-div-class="border-b-[length:var(--border)] border-b-base-content/10 flex flex-wrap overflow-x-auto">
                @foreach (LocalesHelper::locales() as $language)
                    <x-tab :name="$language" :label="__('languages.' . $language)" class="pb-0">
                        <x-input :label="__('activities.form.title')" wire:model="title.{{ $language }}"  />
                    </x-tab>
                @endforeach
                    <div class="px-1">
                        <!-- Core Details -->
                        <x-select :label="__('activities.form.activity_type')" wire:model="activity_type" :options="$types" option-value="value" option-label="label" />
                        <x-input :label="__('activities.form.activity_code')" wire:model="activity_code" :placeholder="__('activities.placeholders.activity_code')" />
                        <x-group :label="__('activities.form.status')" wire:model="status" :options="$statuses" />
                        <x-select :label="__('activities.form.campus')" wire:model="campus_id" :options="$campuses" option-value="value" option-label="label"  />
                        <x-select :label="__('activities.form.discipline')" wire:model="discipline" :options="$disciplines" option-value="value" option-label="label" />
                        <x-select :label="__('activities.form.attribute')" wire:model="attribute" :options="$attributes" option-value="value" option-label="label"  />
                        
                        <!-- Instructor & Staff -->
                        <x-select :label="__('activities.form.instructor')" wire:model="instructor" :options="$instructors" option-value="id" option-label="name" />
                        <x-select :label="__('activities.form.responsible_staff')" wire:model="responsible_staff" :options="$staff" option-value="id" option-label="name" />
                        
                        <!-- Execution Period -->
                        <x-datepicker :label="__('activities.form.execution_from')" wire:model="execution_from"  />
                        <x-datepicker :label="__('activities.form.execution_to')" wire:model="execution_to"  />
                        
                        <!-- Time Slot -->
                        <x-datepicker :label="__('activities.form.time_slot_from_date')" wire:model="time_slot_from_date" />
                        <x-input type="time" :label="__('activities.form.time_slot_from_time')" wire:model.live="time_slot_from_time" />
                        <x-datepicker :label="__('activities.form.time_slot_to_date')" wire:model="time_slot_to_date" />
                        <x-input type="time" :label="__('activities.form.time_slot_to_time')" wire:model.live="time_slot_to_time" />
                        <x-input type="number" :label="__('activities.form.duration_hours')" wire:model="duration_hours" min="0" step="0.5"  />


                        <!-- Program & Venue -->
                        <x-checkbox :label="__('activities.form.swpd_programme')" wire:model="swpd_programme" />
                        <x-input :label="__('activities.form.venue')" wire:model="venue" />
                        
                        <!-- Capacity -->
                        <x-input type="number" :label="__('activities.form.capacity')" wire:model="capacity" min="0" />
                        <x-input type="number" :label="__('activities.form.registered')" wire:model="registered" min="0" />
                        
                        <!-- Financials -->
                        <x-input type="number" :label="__('activities.form.total_amount')" wire:model="total_amount" min="0" step="0.01" />
                        <x-input type="number" :label="__('activities.form.included_deposit')" wire:model="included_deposit" min="0" step="0.01" />
                        
                        <!-- Attachment -->
                        <div class="space-y-3">
                            <x-file 
                                :label="__('activities.form.attachment')" 
                                wire:model="attachment" 
                                :hint="__('activities.form.attachment_hint')" 
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" 
                            />
                            
                            @if ($attachment_display)
                                <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-file-pdf text-error"></i>
                                        <span class="font-medium">{{ $attachment_display }}</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <x-button icon="fal.file-lines"
                                            link="{{ $this->getAttachmentUrl() }}" 
                                            external
                                            class="btn btn-sm btn-primary"
                                            title="{{ __('actions.view') }}"
                                        />
                                        <x-button
                                            icon="fal.trash"
                                            type="button"
                                            wire:click="deleteAttachment"
                                            wire:confirm="{{ __('activities.messages.confirm_delete_file') }}"
                                            class="btn btn-sm btn-erro"
                                            title="{{ __('actions.delete') }}"
                                        />
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @foreach (LocalesHelper::locales() as $language)
                    <x-tab :name="$language" :label="__('languages.' . $language)" class="pt-0">
                        <x-editor :label="__('activities.form.description')" wire:model="description.{{ $language }}" gplLicense />
                        <x-textarea :label="__('activities.form.venue_remark')" wire:model="venue_remark.{{ $language }}" />
                    </x-tab>
                @endforeach
            </x-tabs>

            <x-slot:actions>
                <x-button :label="__('actions.cancel')" :link="route('dashboard.activities.list')" />
                <x-button :label="__($exists ? 'actions.save' : 'actions.create')" :icon="'fal.' . ($exists ? 'floppy-disk' : 'plus')" type="submit" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
