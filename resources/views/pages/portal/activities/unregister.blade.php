<?php

use App\Models\Activity;
use App\Models\ActivityRegistration;
use App\Models\CalendarEvent;
use App\Enums\CalendarEventType;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mary\Traits\Toast;

new
#[Layout("layouts::portal")]
class extends Component
{
    use Toast;

    public Activity $activity;

    public function mount($id)
    {
        $this->activity = Activity::findOrFail($id);
        // Auto-unregister when component loads
        $this->unregister();
    }

    public function unregister()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            $this->error(__('activities.registrations.no_student_profile'));
            return;
        }

        try {
            // Delete from calendar
            CalendarEvent::where('student_id', $student->id)
                ->where('title', 'like', '%' . $this->activity->activity_code . '%')
                ->where('type', CalendarEventType::ACTIVITY)
                ->delete();

            // Delete registration
            ActivityRegistration::where('activity_id', $this->activity->id)
                ->where('student_id', $student->id)
                ->delete();

            $this->success(__('activities.registrations.unregistered'));
            $this->redirect(route('portal.activities.show', $this->activity), navigate: true);
        } catch (\Exception $e) {
            $this->error('Unregistration failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('pages.portal.activities.unregister');
    }
}; ?>

<div class="flex items-center justify-center min-h-screen">
    <div class="text-center">
        <h1 class="text-2xl font-bold mb-4">{{ __('activities.registrations.cancel') }}</h1>
        <p class="mb-6">{{ __('activities.registrations.processing') ?? 'Processing your request...' }}</p>
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto"></div>
    </div>
</div>
