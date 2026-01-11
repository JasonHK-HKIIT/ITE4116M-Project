<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::portal')] class extends Component {}; ?>

<div class="p-4 md:p-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- WELCOME CARD -->
        <x-card class="lg:col-span-3" shadow>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-primary">
                        Welcome back, {{ auth()->user()->given_name }}!
                    </h2>
                    <p class="text-base-content/60 mt-2">
                        {{ now()->format('d F Y (l)') }}
                    </p>
                    <div class="flex items-center gap-2 mt-4">
                        <x-icon name="s-envelope" class="w-9 h-9 text-green-500" />
                        <x-alert title="You have 10 messages" icon="o-exclamation-triangle" />
                    </div>
                </div>
                <x-icon name="fal.sparkles" class="w-16 h-16 text-warning opacity-50" />
            </div>
        </x-card>

        <!-- QUICK ACCESS CARDS -->
        <!-- Calendar -->
        <a href="#" wire:navigate>
            <x-card shadow class="hover:shadow-lg transition cursor-pointer h-full">
                <div class="flex flex-col items-center gap-3 p-4">
                    <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                        <x-icon name="fal.calendar-lines" class="w-8 h-8 text-blue-600 dark:text-blue-400" />
                    </div>
                    <span class="text-base font-semibold text-center">Calendar</span>
                </div>
            </x-card>
        </a>

        <!-- Student Activities -->
        <a href="{{ route('portal.activities.list') }}" wire:navigate>
            <x-card shadow class="hover:shadow-lg transition cursor-pointer h-full">
                <div class="flex flex-col items-center gap-3 p-4">
                    <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                        <x-icon name="fal.ballot" class="w-8 h-8 text-green-600 dark:text-green-400" />
                    </div>
                    <span class="text-base font-semibold text-center">Student Activities</span>
                </div>
            </x-card>
        </a>

        <!-- News & Announcement -->
        <a href="{{route('portal.news.list') }}" wire:navigate>
            <x-card shadow class="hover:shadow-lg transition cursor-pointer h-full">
                <div class="flex flex-col items-center gap-3 p-4">
                    <div
                        class="w-16 h-16 rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center">
                        <x-icon name="fal.bullhorn" class="w-8 h-8 text-orange-600 dark:text-orange-400" />
                    </div>
                    <span class="text-base font-semibold text-center">News & Announcement</span>
                </div>
            </x-card>
        </a>
    </div>

    <!-- Carousel -->
    <div class="mt-6 pt-8">
        @php
            $slides = [
                [
                    'image' => '/Images/VTC_slide1.jpg',
                    'title' => 'VTC Apprenticeship',
                    'description' => 'Combining hands-on training with academic learning for career success.',
                    'url' => '/news/vtc...1',
                ],
                [
                    'image' => '/Images/VTC_slide2.jpg',
                    'title' => 'Support Services for SEN Students',
                    'description' =>
                        'Providing comprehensive support and resources for students with special educational needs.',
                    'url' => '/news/vtc...2',
                ],
                [
                    'image' => '/Images/VTC_slide3.jpg',
                    'title' => 'Programme Selection Info Day',
                    'description' => 'Join us on May 10th to explore programme options and career pathways.',
                    'url' => '/news/vtc...3',
                ],
                [
                    'image' => '/Images/VTC_slide4.jpg',
                    'title' => 'DSE Results Release & Workshop',
                    'description' =>
                        'Analyse exam strategies, get university consultation, test your potential and discover university life.',
                    'url' => '/news/vtc...4',
                ],
                [
                    'image' => '/Images/VTC_slide5.jpg',
                    'title' => 'Curriculum & Experience Day',
                    'description' =>
                        'InfoDay on November 8-9 and 15-16 (Friday-Saturday). Explore our diverse programmes and discover your future.',
                    'url' => '/news/vtc...5',
                ],
            ];
        @endphp

        <x-carousel :slides="$slides" class="h-64 md:h-80 lg:h-96" autoplay="true" interval="8000" />

    </div>

    <!-- Services Section -->
    <div class="mt-6 pt-10">
        <!-- Section Title -->
        <h2 class="text-2xl font-bold text-primary mb-6">Online Student Service</h2>
        <!-- My Academics -->
        <div class="mt-6 pt-4 rounded-lg shadow-md p-6">
            <div class="flex items-center gap-6">
                <div class="flex flex-col items-center gap-2 flex-shrink-0">
                    <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                        <x-icon name="fal.book" class="w-8 h-8 text-blue-600 dark:text-blue-400" />
                    </div>
                    <h3 class="text-lg font-bold text-primary">My Academics</h3>
                </div>
                <div class="flex flex-wrap items-center gap-6">
                    <a href="{{ route('portal.profile.personal-particular') }}" wire:navigate class="text-primary hover:underline">Profile</a>
                </div>
            </div>
        </div>

        <!-- Student Affairs -->
        <div class="mt-6 pt-4 rounded-lg shadow-md p-6">
            <div class="flex items-center gap-6">
                <div class="flex flex-col items-center gap-2 flex-shrink-0">
                    <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                        <x-icon name="fal.users" class="w-8 h-8 text-green-600 dark:text-green-400" />
                    </div>
                    <h3 class="text-lg font-bold text-primary">Student Affairs</h3>
                </div>
                <div class="flex flex-wrap items-center gap-6">
                    <a href="{{ route('portal.news.list') }}" class="text-primary hover:underline">News and Announcement</a>
                    <a href="#" class="text-primary hover:underline">Calendar</a>
                </div>
            </div>
        </div>

        <!-- Campus Life -->
        <div class="mt-6 pt-4 rounded-lg shadow-md p-6">
            <div class="flex items-center gap-6">
                <div class="flex flex-col items-center gap-2 flex-shrink-0">
                    <div
                        class="w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                        <x-icon name="fal.map-pin" class="w-8 h-8 text-purple-600 dark:text-purple-400" />
                    </div>
                    <h3 class="text-lg font-bold text-primary">Campus Life</h3>
                </div>
                <div class="flex flex-wrap items-center gap-6">
                    <a href="{{ route('portal.activities.list') }}" class="text-primary hover:underline">Student Activity</a>
                </div>
            </div>
        </div>

        <!-- Other Services -->
        <div class="mt-6 pt-4 rounded-lg shadow-md p-6">
            <div class="flex items-center gap-6">
                <div class="flex flex-col items-center gap-2 flex-shrink-0">
                    <div
                        class="w-16 h-16 rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center">
                        <x-icon name="fal.cogs" class="w-8 h-8 text-orange-600 dark:text-orange-400" />
                    </div>
                    <h3 class="text-lg font-bold text-primary">Other Services</h3>
                </div>
                <div class="flex flex-wrap items-center gap-6">
                    <a href="{{ route('portal.information-centre') }}" class="text-primary hover:underline" wire:navigate>Information Centre</a>
                </div>
            </div>
        </div>

        <!-- Useful Links -->
        <div class="mt-6 pt-4 rounded-lg shadow-md p-6">
            <div class="flex items-center gap-6">
                <div class="flex flex-col items-center gap-2 flex-shrink-0">
                    <div
                        class="w-16 h-16 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center">
                        <x-icon name="fal.link" class="w-8 h-8 text-yellow-600 dark:text-yellow-400" />
                    </div>
                    <h3 class="text-lg font-bold text-primary">Useful Links</h3>
                </div>
                <div class="flex flex-wrap items-center gap-6">
                    <a href="#" class="text-primary hover:underline">VTC Valo</a>
                </div>
            </div>
        </div>
    </div>
</div>
