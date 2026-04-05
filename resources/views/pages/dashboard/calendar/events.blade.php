<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    
}; ?>

<div>
    <x-header :title="__('Add Events')" :subtitle="__('Calendar')" separator />

    <x-card shadow>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <!-- Class -->
            <button type="button" class="text-left">
                <x-card class="h-full opacity-60 cursor-not-allowed">
                    <div class="flex flex-col items-center text-center gap-3 py-6">
                        <x-icon name="fal.table-cells" class="w-10 h-10" />
                        <div class="font-semibold">Class</div>
                        <div class="text-xs text-base-content/70">Add class timetable events</div>
                    </div>
                </x-card>
            </button>

            <!-- Activity -->
            <button type="button" class="text-left">
                <x-card class="h-full opacity-60 cursor-not-allowed">
                    <div class="flex flex-col items-center text-center gap-3 py-6">
                        <x-icon name="fal.calendar-star" class="w-10 h-10" />
                        <div class="font-semibold">Activity</div>
                        <div class="text-xs text-base-content/70">Add student activities</div>
                    </div>
                </x-card>
            </button>

            <!-- Institute Holiday -->
            <button type="button" class="text-left">
                <x-card class="h-full opacity-60 cursor-not-allowed">
                    <div class="flex flex-col items-center text-center gap-3 py-6">
                        <x-icon name="fal.building-columns" class="w-10 h-10" />
                        <div class="font-semibold">Institute Holiday</div>
                        <div class="text-xs text-base-content/70">Add institute holidays</div>
                    </div>
                </x-card>
            </button>

            <!-- Public Holiday -->
            <button type="button" class="text-left">
                <x-card class="h-full opacity-60 cursor-not-allowed">
                    <div class="flex flex-col items-center text-center gap-3 py-6">
                        <x-icon name="fal.earth-asia" class="w-10 h-10" />
                        <div class="font-semibold">Public Holiday</div>
                        <div class="text-xs text-base-content/70">Add public holidays</div>
                    </div>
                </x-card>
            </button>
        </div>
    </x-card>
</div>
