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
        // Auto-register when component loads
        $this->register();
    }

    public function register()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            $this->error(__('activities.registrations.no_student_profile'));
            return;
        }

        if ($this->isAlreadyRegistered()) {
            $this->error(__('activities.registrations.already_registered'));
            return;
        }

        try {
            ActivityRegistration::create([
                'activity_id' => $this->activity->id,
                'student_id' => $student->id,
                'status' => 'participate',
            ]);

            // Add to calendar
            $fromDate = $this->activity->time_slot_from_date;
            $fromTime = $this->activity->time_slot_from_time;
            $toDate = $this->activity->time_slot_to_date;
            $toTime = $this->activity->time_slot_to_time;

            $startAt = $fromDate->copy()->setTime($fromTime->hour, $fromTime->minute);
            $endAt = $toDate->copy()->setTime($toTime->hour, $toTime->minute);

            $translated = $this->activity->translate('en');
            $activityTitle = $translated?->title ?? '';
            $activityDescription = $translated?->description ?? '';
            $activityCode = $this->activity->activity_code;

            $calendarTitle = $activityCode
                ? $activityCode . ' - ' . $activityTitle
                : $activityTitle;

            CalendarEvent::create([
                'student_id' => $student->id,
                'type' => CalendarEventType::ACTIVITY,
                'title' => $calendarTitle,
                'description' => $activityDescription,
                'location' => $this->activity->venue,
                'instructor' => $this->activity->instructor,
                'start_at' => $startAt,
                'end_at' => $endAt,
            ]);

            $this->success(__('activities.registrations.registered'));
            $this->redirect(route('portal.activities.show', $this->activity), navigate: true);
        } catch (\Exception $e) {
            $this->error('Registration failed: ' . $e->getMessage());
        }
    }

    public function unregister()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
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

    private function isAlreadyRegistered(): bool
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return false;
        }

        return ActivityRegistration::where('activity_id', $this->activity->id)
            ->where('student_id', $student->id)
            ->exists();
    }

    public function render()
    {
        return view('pages.portal.activities.register');
    }
}; ?>

<div class="flex items-center justify-center min-h-screen">
    <div class="text-center">
        <h1 class="text-2xl font-bold mb-4">{{ __('activities.registrations.register') }}</h1>
        <p class="mb-6">{{ __('activities.registrations.processing') ?? 'Processing your registration...' }}</p>
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto"></div>
    </div>
</div>

