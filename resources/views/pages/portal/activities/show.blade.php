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

    public function mount($id)
    {
        $this->activity = Activity::findOrFail($id);
    }

    public function render()
    {
        return view('pages.portal.activities.show');
    }

}; ?>

<div>
    <x-card title="Activity Details" shadow separator>
        <x-tabs wire:model="selectedTab" label-class="text-xl font-bold">
            <x-tab name="administrative-tab" label="Administrative">
                <div class="space-y-6 text-gray-700 dark:text-gray-200 text-lg">
                    <p><strong>Activity Code:</strong> {{ $activity->activity_code }}</p>
                    <p><strong>Activity Type:</strong> {{ $activity->activity_type ?? 'N/A' }}</p>
                    <p><strong>Title:</strong> {{ $activity->title }}</p>

                    <p><strong>Campus:</strong> {{ $activity->campus->name ?? 'N/A' }}</p>
                    <p><strong>Discipline:</strong> {{ $activity->discipline ?? 'N/A' }}</p>
                </div>
            </x-tab>
            <x-tab name="time-tab" label="Time">
                <div class="space-y-6 text-gray-700 dark:text-gray-200 text-lg">
                    <p><strong>Execution Period:</strong> 
                        From {{ $activity->execution_from }} 
                        To {{ $activity->execution_to }}
                    </p>

                    <p><strong>Time Slot:</strong> 
                        From {{ $activity->time_slot_from }} 
                        To {{ $activity->time_slot_to }} 
                        Duration(hr): {{ $activity->duration_hours }}
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
                    <p>{{ $activity->description }}</p>
                    <p><strong>Venue:</strong> {{ $activity->venue }}</p>
                    <p><strong>Venue Remark:</strong> {{ $activity->venue_remark }}</p>
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
                    <p><strong>Attachment:</strong> {{ $activity->attachment }}</p>
                    <p><strong>SWPD Programme:</strong> {{ $activity->swpd_programme ? 'Yes' : 'No' }}</p>
                </div>
            </x-tab>
        </x-tabs>
    </x-card>
</div>
