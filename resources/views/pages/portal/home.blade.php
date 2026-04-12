<?php

use App\Models\CarouselSlide;
use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout('layouts::portal')]
class extends Component
{
    public function carouselSlides(): array
    {
        return CarouselSlide::query()
            ->where('is_active', true)
            ->orderBy('position')
            ->orderBy('id')
            ->get()
            ->map(fn ($slide) => [
                'image' => $slide->getFirstMediaUrl('image'),
                'title' => $slide->title,
                'description' => $slide->description,
                'url' => $slide->link_url ?: '#',
            ])
            ->values()
            ->toArray();
    }

    public function with(): array
    {
        return [
            'slides' => $this->carouselSlides(),
        ];
    }
}; ?>

<div class="p-4 md:p-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- WELCOME CARD -->
        <x-card class="lg:col-span-3" shadow>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-base-content">
                        {{ __('home.welcome_back', ['name' => auth()->user()->given_name]) }}
                    </h2>
                    <p class="text-base-content/80 dark:text-base-content/70 mt-2">
                        {{ now()->format('d F Y (l)') }}
                    </p>
                </div>
                <x-icon name="fal.sparkles" class="w-16 h-16 text-warning opacity-50" />
            </div>
        </x-card>

        <!-- QUICK ACCESS CARDS -->
        <!-- Calendar -->
        <a href="{{ route('portal.calendar') }}" wire:navigate>
            <x-card shadow class="hover:shadow-lg transition cursor-pointer h-full">
                <div class="flex flex-col items-center gap-3 p-4">
                    <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                        <x-icon name="fal.calendar-lines" class="w-8 h-8 text-blue-600 dark:text-blue-400" />
                    </div>
                    <span class="text-base font-semibold text-center text-base-content">{{ __('home.links.calendar') }}</span>
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
                    <span class="text-base font-semibold text-center text-base-content">{{ __('navigation.portal.activities') }}</span>
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
                    <span class="text-base font-semibold text-center text-base-content">{{ __('home.links.news_and_announcement') }}</span>
                </div>
            </x-card>
        </a>
    </div>

    <!-- Carousel -->
    <div class="mt-6 pt-8">
        <x-carousel :slides="$slides" class="h-64 md:h-80 lg:h-96" autoplay="true" interval="8000" />
    </div>

    <!-- Services Section -->
    <div class="mt-6 pt-10">
        <!-- Section Title -->
        <h2 class="text-2xl font-bold text-base-content mb-6">{{ __('home.online_student_service') }}</h2>
        <!-- My Academics -->
        <x-card class="mt-6 pt-4 p-6" shadow>
            <div class="flex items-center gap-6">
                <div class="min-w-35 flex flex-col items-center gap-2 flex-shrink-0">
                    <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                        <x-icon name="fal.book" class="w-8 h-8 text-blue-600 dark:text-blue-400" />
                    </div>
                    <h3 class="text-lg font-bold text-base-content">{{ __('home.services.my_academics') }}</h3>
                </div>
                <div class="flex flex-wrap items-center gap-6">
                    <a href="{{ route('portal.profile.personal-particular') }}" wire:navigate class="text-base-content hover:text-primary hover:underline transition-colors">{{ __('home.links.profile') }}</a>
                </div>
            </div>
        </x-card>

        <!-- Student Affairs -->
        <x-card class="mt-6 pt-4 p-6" shadow>
            <div class="flex items-center gap-6">
                <div class="min-w-35 flex flex-col items-center gap-2 flex-shrink-0">
                    <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                        <x-icon name="fal.users" class="w-8 h-8 text-green-600 dark:text-green-400" />
                    </div>
                    <h3 class="text-lg font-bold text-base-content">{{ __('home.services.student_affairs') }}</h3>
                </div>
                <div class="flex flex-wrap items-center gap-6">
                    <a href="{{ route('portal.news.list') }}" class="text-base-content hover:text-primary hover:underline transition-colors">{{ __('home.links.news_and_announcement') }}</a>
                    <a href="{{ route('portal.calendar') }}" class="text-base-content hover:text-primary hover:underline transition-colors">{{ __('home.links.calendar') }}</a>
                </div>
            </div>
        </x-card>

        <!-- Campus Life -->
        <x-card class="mt-6 pt-4 p-6" shadow>
            <div class="flex items-center gap-6">
                <div class="min-w-35 flex flex-col items-center gap-2 flex-shrink-0">
                    <div
                        class="w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                        <x-icon name="fal.map-pin" class="w-8 h-8 text-purple-600 dark:text-purple-400" />
                    </div>
                    <h3 class="text-lg font-bold text-base-content">{{ __('home.services.campus_life') }}</h3>
                </div>
                <div class="flex flex-wrap items-center gap-6">
                    <a href="{{ route('portal.activities.list') }}" class="text-base-content hover:text-primary hover:underline transition-colors">{{ __('home.links.student_activity') }}</a>
                </div>
            </div>
        </x-card>

        <!-- Other Services -->
        <x-card class="mt-6 pt-4 p-6" shadow>
            <div class="flex items-center gap-6">
                <div class="min-w-35 flex flex-col items-center gap-2 flex-shrink-0">
                    <div
                        class="w-16 h-16 rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center">
                        <x-icon name="fal.cogs" class="w-8 h-8 text-orange-600 dark:text-orange-400" />
                    </div>
                    <h3 class="text-lg font-bold text-base-content">{{ __('home.services.other_services') }}</h3>
                </div>
                <div class="flex flex-wrap items-center gap-6">
                    <a href="{{ route('portal.resources-centre') }}" class="text-base-content hover:text-primary hover:underline transition-colors" wire:navigate>{{ __('home.links.resources_centre') }}</a>
                </div>
            </div>
        </x-card>

        <!-- Useful Links -->
        <x-card class="mt-6 pt-4 p-6" shadow>
            <div class="flex items-center gap-6">
                <div class="min-w-35 flex flex-col items-center gap-2 flex-shrink-0">
                    <div
                        class="w-16 h-16 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center">
                        <x-icon name="fal.link" class="w-8 h-8 text-yellow-600 dark:text-yellow-400" />
                    </div>
                    <h3 class="text-lg font-bold text-base-content">{{ __('home.services.useful_links') }}</h3>
                </div>
                <div class="flex flex-wrap items-center gap-6">
                    <a href="#" class="text-base-content hover:text-primary hover:underline transition-colors">{{ __('home.links.vtc_valo') }}</a>
                </div>
            </div>
        </x-card>
    </div>
</div>
