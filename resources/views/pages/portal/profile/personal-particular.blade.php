<?php

use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::portal')] class extends Component {
    public ?Student $student = null;

    public function mount(): void
    {
        $this->student = Auth::user()->student;
    }
}; ?>

@php
    $user = Auth::user();

    $student = $this->student;
    // Take the latest class
    $currentClass = $student?->classes()->orderByDesc('academic_year')->first();

    $instituteName = $student?->institute?->name ?? '';

    $campusName = $student?->campus?->name ?? '';

    $rows = [
        ['label' => __('profile.personal_particular.fields.student_id'), 'value' => $user->username],
        ['label' => __('profile.personal_particular.fields.chinese_name'), 'value' => $user->chinese_name],
        ['label' => __('profile.personal_particular.fields.family_name'), 'value' => $user->family_name],
        ['label' => __('profile.personal_particular.fields.given_name'), 'value' => $user->given_name],
        ['label' => __('profile.personal_particular.fields.institute'), 'value' => $instituteName],
        ['label' => __('profile.personal_particular.fields.campus'), 'value' => $campusName],
        ['label' => __('profile.personal_particular.fields.current_class'), 'value' => $currentClass?->class_code],
        ['label' => __('profile.personal_particular.fields.gender'), 'value' => $student?->gender],
        ['label' => __('profile.personal_particular.fields.date_of_birth'), 'value' => $student?->date_of_birth?->format('d-M-Y')],
        ['label' => __('profile.personal_particular.fields.country'), 'value' => $student?->nationality],
        ['label' => __('profile.personal_particular.fields.native_language'), 'value' => $student?->mother_tongue],
        ['label' => __('profile.personal_particular.fields.tel_no'), 'value' => $student?->tel_no],
        ['label' => __('profile.personal_particular.fields.mobile_no'), 'value' => $student?->mobile_no],
        ['label' => __('profile.personal_particular.fields.correspondence_address_hk'), 'value' => $student?->address],
    ];
@endphp


<div class="p-4 md:p-8 space-y-6 max-w-7xl mx-auto">
  <x-header :title="__('profile.title')" separator />
    <x-card shadow class="overflow-hidden">
        <div class="flex flex-col lg:flex-row gap-6">
            <div class="flex-1 space-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-primary">{{ __('profile.personal_particular.title') }}</h2>
                        <p class="text-sm text-base-content/60">{{ __('profile.personal_particular.description') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach ($rows as $row)
                        <div class="flex gap-3 border-b border-base-200 py-2">
                            <span class="w-48 text-sm font-semibold text-base-content/70">{{ $row['label'] }}</span>
                            <span class="text-sm text-base-content">{{ ($row['value'] === null || $row['value'] === '') ? __('profile.personal_particular.not_available') : $row['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="w-full md:w-48 lg:w-56 flex-shrink-0 mx-auto lg:mx-0">
                <div class=" shadow-lg overflow-hidden ">
                    <img src="{{ $user->avatar}}" alt="{{ __('profile.personal_particular.photo_alt') }}" class="w-full h-full object-cover object-center">
                </div>
            </div>
        </div>
    </x-card>
</div>
