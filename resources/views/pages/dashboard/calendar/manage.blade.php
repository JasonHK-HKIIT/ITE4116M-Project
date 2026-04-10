<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    
}; ?>

<div>
    <x-header :title="__('calendar.manage.title')" :subtitle="__('calendar.manage.subtitle')" separator />

    <x-card shadow>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <!-- Module -->
            <a href="{{ route('dashboard.calendar.classes') }}" class="no-underline">
                <x-card class="h-full cursor-pointer hover:bg-base-200 transition-colors">
                    <div class="flex flex-col items-center text-center gap-3 py-6">
                        <x-icon name="fal.table-cells-large" class="w-10 h-10 text-primary" />
                        <div class="font-semibold">Module Timetable</div>
                        <div class="text-xs text-base-content/70">Manage class timetable by module</div>
                    </div>
                </x-card>
            </a>

            <!-- Activity -->
            <a href="{{ route('dashboard.calendar.activities') }}" class="no-underline">
                <x-card class="h-full cursor-pointer hover:bg-base-200 transition-colors">
                    <div class="flex flex-col items-center text-center gap-3 py-6">
                        <x-icon name="fal.calendar-star" class="w-10 h-10 text-primary" />
                        <div class="font-semibold">Activities</div>
                        <div class="text-xs text-base-content/70">View student activities</div>
                    </div>
                </x-card>
            </a>

            <!-- Institute Holiday -->
            <a href="{{ route('dashboard.calendar.institute_holidays') }}" class="no-underline">
                <x-card class="h-full cursor-pointer hover:bg-base-200 transition-colors">
                    <div class="flex flex-col items-center text-center gap-3 py-6">
                        <x-icon name="fal.building-columns" class="w-10 h-10 text-primary" />
                        <div class="font-semibold">Institute Holidays</div>
                        <div class="text-xs text-base-content/70">View and edit institute holidays</div>
                    </div>
                </x-card>
            </a>

            <!-- Public Holiday -->
            <a href="{{ route('dashboard.calendar.public_holidays') }}" class="no-underline">
                <x-card class="h-full cursor-pointer hover:bg-base-200 transition-colors">
                    <div class="flex flex-col items-center text-center gap-3 py-6">
                        <x-icon name="fal.earth-asia" class="w-10 h-10 text-primary" />
                        <div class="font-semibold">Public Holidays</div>
                        <div class="text-xs text-base-content/70">View and edit public holidays</div>
                    </div>
                </x-card>
            </a>
        </div>
    </x-card>
</div>
