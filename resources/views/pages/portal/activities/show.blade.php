<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Activity;

new
#[Layout("layouts::portal")]
class extends Component
{
    public Activity $activity;

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
        
        <p><strong>SWPD Programme:</strong> {{ $activity->swpd_programme ? 'Yes' : 'No' }}</p>

        <p><strong>Execution Period:</strong> 
            From {{ $activity->execution_from }} 
            To {{ $activity->execution_to }}
        </p>

        <p><strong>Time Slot:</strong> 
            From {{ $activity->time_slot_from }} 
            To {{ $activity->time_slot_to }} 
            Duration(hr): {{ $activity->duration_hours }}
        </p>

        <p><strong>Instructor:</strong> {{ $activity->instructor }}</p>
        <p><strong>Responsible Staff:</strong> {{ $activity->responsible_staff }}</p>

        <p><strong>Venue:</strong> {{ $activity->venue }}</p>
        <p><strong>Venue Remark:</strong> {{ $activity->venue_remark }}</p>

        <p><strong>Total Amount:</strong> {{ $activity->total_amount }}</p>
        <p><strong>Included Deposit:</strong> {{ $activity->included_deposit }}</p>

        <p><strong>Attachment:</strong> {{ $activity->attachment }}</p>

        <x-button label="Back" :link="route('portal.activities.list')" class="btn-ghost mt-4" />
    </div>
    </x-card>
</div>
