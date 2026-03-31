<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Activity;

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

}; ?>

<div>
    <x-card title="Activity Details" shadow separator>
        <x-tabs wire:model="selectedTab" label-class="text-xl font-bold">
            <x-tab name="administrative-tab" label="Administrative">
                <div class="space-y-6 text-gray-700 dark:text-gray-200 text-lg">
                    <p><strong>Activity Type:</strong> {{ $activity->activity_type ?? 'N/A' }}</p>
                    <p><strong>Activity Code:</strong> {{ $activity->activity_code ?? 'N/A' }}</p>
                    <p><strong>Title:</strong> {{ $activity->title }}</p>

                    <p><strong>Campus:</strong> {{ $activity->campus->name ?? 'N/A' }}</p>
                    <p><strong>Discipline:</strong> {{ $this->getDisciplineLabel() }}</p>
                    <p><strong>Attribute:</strong> {{ $this->getAttributeLabel() }}</p>
                </div>
            </x-tab>
            <x-tab name="time-tab" label="Time">
                <div class="space-y-6 text-gray-700 dark:text-gray-200 text-lg">
                    <p><strong>Execution Period:</strong> 
                        From {{ $activity->execution_from?->format('M d, Y') ?? 'N/A' }} 
                        To {{ $activity->execution_to?->format('M d, Y') ?? 'N/A' }}
                    </p>

                    <p><strong>Time Slot:</strong> 
                        From {{ $activity->time_slot_from_date?->format('M d, Y') ?? 'N/A' }} at {{ \Illuminate\Support\Carbon::parse($activity->time_slot_from_time)?->format('H:i') ?? 'N/A' }}
                        To {{ $activity->time_slot_to_date?->format('M d, Y') ?? 'N/A' }} at {{ \Illuminate\Support\Carbon::parse($activity->time_slot_to_time)?->format('H:i') ?? 'N/A' }}
                        <br><strong>Duration: </strong>{{ $activity->duration_hours }} hours
                    </p>
                </div>
            </x-tab>
            <x-tab name="personnel-tab" label="Personnel">
                <div class="space-y-6 text-gray-700 dark:text-gray-200 text-lg">
                    <p><strong>Instructor:</strong> {{ $activity->instructor }}</p>
                    <p><strong>Responsible Staff:</strong> {{ $activity->responsible_staff }}</p>
                </div>
            </x-tab>
            <x-tab name="descriptive-tab" label="Descriptive">
                <div class="space-y-3 text-gray-700 dark:text-gray-200 text-lg">
                    <p><strong>Description:</strong></p>
                    <div class="prose max-w-none">{!! $activity->description !!}</div>
                    <p><strong>Venue:</strong> {{ $activity->venue }}</p>
                    <p><strong>Venue Remark:</strong> {{ $activity->venue_remark ?? 'N/A' }}</p>
                </div>
            </x-tab>
            <x-tab name="financial-tab" label="Financial">
                <div class="space-y-6 text-gray-700 dark:text-gray-200 text-lg">
                    <p><strong>Total Amount:</strong> {{ $activity->total_amount }}</p>
                    <p><strong>Included Deposit:</strong> {{ $activity->included_deposit }}</p>
                </div>
            </x-tab>
            <x-tab name="supporting-tab" label="Supporting">
                <div class="space-y-6 text-gray-700 dark:text-gray-200 text-lg">
                    <div>
                        <strong>Attachment:</strong>
                        @if($activity->attachment)
                            <div class="mt-2 space-y-2">
                                @php
                                    $filePath = storage_path('app/public/activities/' . $activity->attachment);
                                    $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                @endphp
                                <div class="flex justify-between items-center bg-base-100 p-3 rounded">
                                    <div>
                                        <p class="font-semibold">{{ $activity->attachment }}</p>
                                        <p class="text-sm text-gray-600">Size: {{ number_format($fileSize / 1024, 2) }} KB</p>
                                    </div>
                                    <a href="{{ asset('storage/activities/' . $activity->attachment) }}" class="btn btn-ghost btn-sm" target="_blank">
                                        <x-icon name="o-arrow-down-tray" class="w-5 h-5" />
                                    </a>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-400 mt-2">No attachment</p>
                        @endif
                    </div>
                    <p><strong>SWPD Programme:</strong> {{ $activity->swpd_programme ? 'Yes' : 'No' }}</p>
                </div>
            </x-tab>
        </x-tabs>
        <x-slot:actions separator>
            <a href="{{ route('portal.activities.list') }}" class="btn btn-primary">{{ __('Back to activities List') }}</a>
        </x-slot:actions>
    </x-card>
</div>
