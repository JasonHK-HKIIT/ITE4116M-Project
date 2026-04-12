<?php

use App\Enums\Role;
use App\Models\Activity;
use App\Models\CalendarEvent;
use App\Models\NewsArticle;
use App\Models\Resource;
use App\Models\Student;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::dashboard')] class extends Component
{
    #[Computed]
    public function totals(): array
    {
        return [
            'students' => Student::query()->count(),
            'activities' => Activity::query()->count(),
            'news' => NewsArticle::query()->count(),
            'resources' => Resource::query()->count(),
            'staff' => User::query()->whereIn('role', [Role::ADMIN->value, Role::STAFF->value])->count(),
            'upcoming_events' => CalendarEvent::query()->where('start_at', '>=', now())->count(),
        ];
    }

    #[Computed]
    public function quickLinks(): array
    {
        $user = auth()->user();

        $links = [
            [
                'title' => __('navigation.dashboard.all_students'),
                'description' => __('dashboard.quick_links.students_description'),
                'icon' => 'fal.users',
                'route' => 'dashboard.students.list',
                'permission' => 'students',
                'accent' => 'text-blue-600',
                'chip' => 'bg-blue-100',
            ],
            [
                'title' => __('navigation.dashboard.all_events'),
                'description' => __('dashboard.quick_links.events_description'),
                'icon' => 'fal.calendar-users',
                'route' => 'dashboard.calendar.manage',
                'permission' => 'calendar',
                'accent' => 'text-indigo-600',
                'chip' => 'bg-indigo-100',
            ],
            [
                'title' => __('navigation.dashboard.all_activities'),
                'description' => __('dashboard.quick_links.activities_description'),
                'icon' => 'fal.calendar-star',
                'route' => 'dashboard.activities.list',
                'permission' => 'activities',
                'accent' => 'text-emerald-600',
                'chip' => 'bg-emerald-100',
            ],
            [
                'title' => __('navigation.dashboard.all_articles'),
                'description' => __('dashboard.quick_links.news_description'),
                'icon' => 'fal.newspaper',
                'route' => 'dashboard.news.list',
                'permission' => 'news',
                'accent' => 'text-amber-600',
                'chip' => 'bg-amber-100',
            ],
            [
                'title' => __('navigation.dashboard.all_resources'),
                'description' => __('dashboard.quick_links.resources_description'),
                'icon' => 'fal.circle-info',
                'route' => 'dashboard.resources.list',
                'permission' => 'resources',
                'accent' => 'text-fuchsia-600',
                'chip' => 'bg-fuchsia-100',
            ],
            [
                'title' => __('navigation.dashboard.staff_members'),
                'description' => __('dashboard.quick_links.staff_description'),
                'icon' => 'fal.user-gear',
                'route' => 'dashboard.system.staff.list',
                'role' => Role::ADMIN,
                'accent' => 'text-rose-600',
                'chip' => 'bg-rose-100',
            ],
        ];

        return collect($links)
            ->filter(function (array $link) use ($user)
            {
                if (isset($link['role'])) {
                    return $user->hasRole($link['role']);
                }

                return $user->hasPermission($link['permission']);
            })
            ->values()
            ->all();
    }

    #[Computed]
    public function upcomingEvents()
    {
        return CalendarEvent::query()
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->limit(6)
            ->get();
    }

    #[Computed]
    public function latestNews()
    {
        return NewsArticle::query()
            ->orderByDesc('published_on')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function latestActivities()
    {
        return Activity::query()
            ->orderByDesc('execution_from')
            ->limit(5)
            ->get();
    }
}; ?>

<div class="p-4 md:p-8 space-y-6 max-w-7xl mx-auto">
    <x-card shadow>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-base-content">
                    {{ __('dashboard.welcome_back', ['name' => auth()->user()->given_name]) }}
                </h1>
                <p class="text-base-content/70 mt-1">
                    {{ __('dashboard.overview_for_date', ['date' => now()->format('F j, Y')]) }}
                </p>
            </div>
            <div class="badge badge-primary badge-outline">{{ __('navigation.dashboard.home') }}</div>
        </div>
    </x-card>

    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
        <x-stat :title="__('navigation.dashboard.students')" :value="$this->totals['students']" icon="fal.users" color="text-info" />
        <x-stat :title="__('navigation.dashboard.activities')" :value="$this->totals['activities']" icon="fal.calendar-star" color="text-success" />
        <x-stat :title="__('navigation.dashboard.news')" :value="$this->totals['news']" icon="fal.newspaper" color="text-warning" />
        <x-stat :title="__('navigation.dashboard.resource_centre')" :value="$this->totals['resources']" icon="fal.circle-info" color="text-secondary" />
        <x-stat :title="__('navigation.dashboard.staff_members')" :value="$this->totals['staff']" icon="fal.user-gear" color="text-error" />
        <x-stat :title="__('navigation.dashboard.all_events')" :value="$this->totals['upcoming_events']" icon="fal.calendar-days" color="text-primary" />
    </div>

    <x-header :title="__('dashboard.quick_actions_title')" :subtitle="__('dashboard.quick_actions_subtitle')" separator />
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($this->quickLinks as $link)
            <a href="{{ route($link['route']) }}" wire:navigate>
                <x-card shadow class="h-full transition hover:-translate-y-0.5 hover:shadow-lg cursor-pointer">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl {{ $link['chip'] }} flex items-center justify-center shrink-0">
                            <x-icon :name="$link['icon']" class="w-5 h-5 {{ $link['accent'] }}" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-base-content">{{ $link['title'] }}</h3>
                            <p class="text-sm text-base-content/70 mt-1">{{ $link['description'] }}</p>
                        </div>
                    </div>
                </x-card>
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <x-card shadow class="xl:col-span-2">
            <x-header :title="__('dashboard.upcoming_events_title')" :subtitle="__('dashboard.upcoming_events_subtitle')" size="text-lg" separator />

            @if ($this->upcomingEvents->isEmpty())
                <p class="text-sm text-base-content/70">{{ __('dashboard.no_upcoming_events') }}</p>
            @else
                <div class="space-y-3">
                    @foreach ($this->upcomingEvents as $event)
                        <div class="flex items-start justify-between gap-4 rounded-lg border border-base-300 p-3">
                            <div class="min-w-0">
                                <p class="font-medium truncate">{{ $event->title }}</p>
                                <p class="text-sm text-base-content/70 truncate">{{ $event->location ?: __('dashboard.location_not_set') }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-medium">{{ $event->start_at?->format('Y-m-d') }}</p>
                                <p class="text-xs text-base-content/70">{{ $event->start_at?->format('H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card>

        <x-card shadow>
            <x-header :title="__('dashboard.latest_news_title')" :subtitle="__('dashboard.latest_news_subtitle')" size="text-lg" separator />

            @if ($this->latestNews->isEmpty())
                <p class="text-sm text-base-content/70">{{ __('dashboard.no_published_articles') }}</p>
            @else
                <div class="space-y-3">
                    @foreach ($this->latestNews as $article)
                        <a href="{{ route('dashboard.news.edit', ['article' => $article]) }}" wire:navigate class="block rounded-lg border border-base-300 p-3 hover:bg-base-200/40 transition">
                            <p class="font-medium line-clamp-2">{{ $article->title }}</p>
                            <p class="text-xs text-base-content/70 mt-1">
                                {{ $article->published_on?->format('Y-m-d') ?? __('dashboard.not_published_date') }}
                            </p>
                        </a>
                    @endforeach
                </div>
            @endif
        </x-card>
    </div>

    <x-card shadow>
        <x-header :title="__('dashboard.recent_activities_title')" :subtitle="__('dashboard.recent_activities_subtitle')" size="text-lg" separator />

        @if ($this->latestActivities->isEmpty())
            <p class="text-sm text-base-content/70">{{ __('dashboard.no_activity_records') }}</p>
        @else
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>{{ __('dashboard.table.title') }}</th>
                            <th>{{ __('dashboard.table.instructor') }}</th>
                            <th>{{ __('dashboard.table.execution') }}</th>
                            <th class="w-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->latestActivities as $activity)
                            <tr>
                                <td class="max-w-56 truncate">{{ $activity->title }}</td>
                                <td>{{ $activity->instructor ?: __('dashboard.not_available') }}</td>
                                <td>
                                    {{ $activity->execution_from?->format('Y-m-d') }}
                                    @if($activity->execution_to)
                                        - {{ $activity->execution_to?->format('Y-m-d') }}
                                    @endif
                                </td>
                                <td>
                                    <x-button icon="fal.arrow-up-right-from-square" class="btn-ghost btn-sm" :link="route('dashboard.activities.edit', ['activity' => $activity->id])" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>
</div>
