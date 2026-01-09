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
    $rows = [
        ['label' => 'Student ID', 'value' => $user->username],
        ['label' => 'Chinese Name', 'value' => $user->chinese_name],
        ['label' => 'Family name', 'value' => $user->family_name],
        ['label' => 'Given Name', 'value' => $user->given_name],
        ['label' => 'Institute Campus ID', 'value' => $student?->institute_campus_id],
        ['label' => 'Gender', 'value' => $student?->gender],
        ['label' => 'Date of Birth', 'value' => $student?->date_of_birth?->format('d-M-Y')],
        ['label' => 'Country', 'value' => $student?->nationality],
        ['label' => 'Native Language ', 'value' => $student?->mother_tongue],
        ['label' => 'Tel No.', 'value' => $student?->tel_no],
        ['label' => 'Mobile No.', 'value' => $student?->mobile_no],
        ['label' => 'Correspondence Address in Hong Kong', 'value' => $student?->address],
    ];
@endphp

<div class="p-4 md:p-8 space-y-6 max-w-7xl mx-auto">
    <x-card shadow class="overflow-hidden">
        <div class="flex flex-col lg:flex-row gap-6">
            <div class="flex-1 space-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-primary">Personal Particular</h2>
                        <p class="text-sm text-base-content/60">View your personal information.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach ($rows as $row)
                        <div class="flex gap-3 border-b border-base-200 py-2">
                            <span class="w-48 text-sm font-semibold text-base-content/70">{{ $row['label'] }}</span>
                            <span class="text-sm text-base-content">{{ $row['value'] ?? '—' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="w-full md:w-48 lg:w-56 flex-shrink-0 mx-auto lg:mx-0">
                <div class="rounded-lg shadow-lg overflow-hidden bg-base-200">
                    <img src="{{ $user->avatar() }}" alt="Student photo" class="w-full h-full object-cover object-center">
                </div>
            </div>
        </div>
    </x-card>
</div>
