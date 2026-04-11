<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Activity;
use App\Enums\Role;
use App\Models\ActivityRegistration;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

new
#[Layout("layouts::portal")]
class extends Component
{
    public Activity $activity;
    public string $selectedTab = 'administrative-tab';
    public string $selectedLanguage = 'en';

    public function mount($id)
    {
        $this->activity = Activity::findOrFail($id);
        $this->selectedLanguage = app()->getLocale();
    }

    public function changeLanguage($lang)
    {
        app()->setLocale($lang);
        $this->selectedLanguage = $lang;
    }

    public function render()
    {
        return view('pages.portal.activities.show');
    }

    public function getDisciplineLabel(): string
    {
        return $this->activity->discipline?->label() ?? 'N/A';
    }

    public function getAttributeLabel(): string
    {
        return $this->activity->attribute?->label() ?? 'N/A';
    }

    public function getAttachmentUrl(): ?string
    {
        if ($this->activity->attachment) {
            $path = "activities/{$this->activity->id}/{$this->activity->attachment}";
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->url($path);
            }
        }
        return null;
    }

    public function getAttachmentSize(): int
    {
        if ($this->activity->attachment) {
            $path = "activities/{$this->activity->id}/{$this->activity->attachment}";
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->size($path);
            }
        }
        return 0;
    }

    public function isAlreadyRegistered(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        $student = Auth::user()->student;
        if (!$student) {
            return false;
        }
        return ActivityRegistration::where('activity_id', $this->activity->id)
            ->where('student_id', $student->id)
            ->exists();
    }

    public function getRegistrationStatus(): ?string
    {
        if (!Auth::check()) {
            return null;
        }
        $student = Auth::user()->student;
        if (!$student) {
            return null;
        }
        return ActivityRegistration::where('activity_id', $this->activity->id)
            ->where('student_id', $student->id)
            ->value('status');
    }

    public function isRegistrationOpen(): bool
    {
        $now = now();
        return $now->isBetween($this->activity->execution_from, $this->activity->execution_to);
    }

    public function hasVacancy(): bool
    {
        return $this->activity->registered < $this->activity->capacity;
    }

    public function isStaffOrAdmin(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        return Auth::user()->hasAnyRole(Role::STAFF, Role::ADMIN);
    }

}; ?>

<div>
    <x-card :title="__('activities.show.title')" shadow separator>
        <x-tabs wire:model="selectedTab" label-class="text-xl font-bold">
            <x-tab name="administrative-tab" :label="__('activities.show.tabs.administrative')">
                <div class="space-y-6 text-gray-700 dark:text-gray-200 text-lg">
                    <p><strong>{{ __('activities.show.fields.activity_type') }}:</strong> {{ $activity->activity_type ?? 'N/A' }}</p>
                    <p><strong>{{ __('activities.show.fields.activity_code') }}:</strong> {{ $activity->activity_code ?? 'N/A' }}</p>
                    <p><strong>{{ __('activities.show.fields.title') }}:</strong> {{ $activity->title }}</p>

                    <p><strong>{{ __('activities.show.fields.campus') }}:</strong> {{ $activity->campus->name ?? 'N/A' }}</p>
                    <p><strong>{{ __('activities.show.fields.discipline') }}:</strong> {{ $this->getDisciplineLabel() }}</p>
                    <p><strong>{{ __('activities.show.fields.attribute') }}:</strong> {{ $this->getAttributeLabel() }}</p>
                </div>
            </x-tab>
            <x-tab name="time-tab" :label="__('activities.show.tabs.time')">
                <div class="space-y-6 text-gray-700 dark:text-gray-200 text-lg">
                    <p><strong>{{ __('activities.show.fields.execution_period') }}:</strong> 
                        From {{ $activity->execution_from?->format('M d, Y') ?? 'N/A' }} 
                        To {{ $activity->execution_to?->format('M d, Y') ?? 'N/A' }}
                    </p>

                    <p><strong>{{ __('activities.show.fields.time_slot') }}:</strong> 
                        From {{ $activity->time_slot_from_date?->format('M d, Y') ?? 'N/A' }} at {{ \Illuminate\Support\Carbon::parse($activity->time_slot_from_time)?->format('H:i') ?? 'N/A' }}
                        To {{ $activity->time_slot_to_date?->format('M d, Y') ?? 'N/A' }} at {{ \Illuminate\Support\Carbon::parse($activity->time_slot_to_time)?->format('H:i') ?? 'N/A' }}
                        <br><strong>{{ __('activities.show.fields.duration') }}: </strong>{{ $activity->duration_hours }} hours
                    </p>
                </div>
            </x-tab>
            <x-tab name="personnel-tab" :label="__('activities.show.tabs.personnel')">
                <div class="space-y-6 text-gray-700 dark:text-gray-200 text-lg">
                    <p><strong>{{ __('activities.show.fields.instructor') }}:</strong> {{ $activity->instructor }}</p>
                    <p><strong>{{ __('activities.show.fields.responsible_staff') }}:</strong> {{ $activity->responsible_staff }}</p>
                </div>
            </x-tab>
            <x-tab name="descriptive-tab" :label="__('activities.show.tabs.descriptive')">
                <div class="space-y-3 text-gray-700 dark:text-gray-200 text-lg">
                    <p><strong>{{ __('activities.show.fields.description') }}:</strong></p>
                    <div class="prose max-w-none">{!! $activity->description !!}</div>
                    <p><strong>{{ __('activities.show.fields.venue') }}:</strong> {{ $activity->venue }}</p>
                    <p><strong>{{ __('activities.show.fields.venue_remark') }}:</strong> {{ $activity->venue_remark ?? 'N/A' }}</p>
                </div>
            </x-tab>
            <x-tab name="financial-tab" :label="__('activities.show.tabs.financial')">
                <div class="space-y-6 text-gray-700 dark:text-gray-200 text-lg">
                    <p><strong>{{ __('activities.show.fields.total_amount') }}:</strong> {{ $activity->total_amount }}</p>
                    <p><strong>{{ __('activities.show.fields.included_deposit') }}:</strong> {{ $activity->included_deposit }}</p>
                </div>
            </x-tab>
            <x-tab name="supporting-tab" :label="__('activities.show.tabs.supporting')">
                <div class="space-y-6 text-gray-700 dark:text-gray-200 text-lg">
                    <div>
                        <strong>{{ __('activities.show.fields.attachment') }}:</strong>
                        @if($activity->attachment)
                            <div class="mt-2 space-y-2">
                                @php
                                    $attachmentUrl = $this->getAttachmentUrl();
                                    $fileSize = $this->getAttachmentSize();
                                @endphp
                                @if($attachmentUrl)
                                    <div class="flex justify-between items-center bg-base-100 p-3 rounded">
                                        <div>
                                            <p class="font-semibold">{{ $activity->attachment }}</p>
                                            <p class="text-sm text-gray-600">{{ __('activities.show.fields.size') }}: {{ number_format($fileSize / 1024, 2) }} KB</p>
                                        </div>
                                        <a href="{{ $attachmentUrl }}" class="btn btn-primary btn-sm text-white" target="_blank" rel="noopener noreferrer">
                                            <x-icon name="o-arrow-down-tray" class="w-5 h-5" />
                                        </a>
                                    </div>
                                @else
                                    <p class="text-warning mt-2">{{ __('activities.messages.file_not_found') }}</p>
                                @endif
                            </div>
                        @else
                            <p class="text-gray-400 mt-2">{{ __('activities.messages.no_attachment') }}</p>
                        @endif
                    </div>
                    <p><strong>{{ __('activities.show.fields.swpd_programme') }}:</strong> {{ $activity->swpd_programme ? __('activities.messages.yes') : __('activities.messages.no') }}</p>
                </div>
            </x-tab>
        </x-tabs>
        <x-slot:actions separator>
            @auth
                @if($this->isAlreadyRegistered())
                    <a href="{{ route('portal.activities.unregister', $activity->id) }}" class="btn btn-error btn-outline">
                        {{ __('activities.registrations.cancel') }}
                    </a>
                @elseif(!$this->isRegistrationOpen())
                    <button class="btn btn-disabled">{{ __('activities.registrations.registration_closed') }}</button>
                @elseif(!$this->hasVacancy())
                    <button class="btn btn-disabled">{{ __('activities.registrations.status.activity_full') }}</button>
                @elseif($this->isStaffOrAdmin())
                    <button class="btn btn-disabled">{{ __('activities.registrations.status.register') }}</button>
                @else
                    <a href="{{ route('portal.activities.register', $activity->id) }}" class="btn btn-primary">
                        {{ __('activities.registrations.status.register') }}
                    </a>
                @endif
            @endauth
            <a href="{{ route('portal.activities.list') }}" class="btn btn-ghost">{{ __('actions.back') }}</a>
        </x-slot:actions>
    </x-card>
</div>
