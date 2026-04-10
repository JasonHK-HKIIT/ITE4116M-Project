@extends("layouts::base")

@section("content")
<x-nav sticky class="lg:hidden">
    <x-slot:brand>
        <livewire:brand />
    </x-slot:brand>
    <x-slot:actions>
        <label for="main-drawer" class="lg:hidden me-3">
            <x-icon name="fal.bars" class="cursor-pointer" />
        </label>
    </x-slot:actions>
</x-nav>

<x-main full-width>
    <x-slot:sidebar drawer="main-drawer" class="bg-base-100 lg:bg-inherit">
        <livewire:brand class="px-5 pt-4" />
        @php($user = auth()->user())

        <x-menu activate-by-route>
            <livewire:sidebar-user />

            <x-menu-item title="Dashboard" icon="fal.gauge-high" route="dashboard.home" />

            @if ($user?->hasPermission('calendar'))
                <x-menu-sub title="Calendar" icon="fal.calendar-users">
                    <x-menu-item title="All Events" route="dashboard.calendar.manage" />
                    <x-menu-item title="Create Event" route="dashboard.calendar.events" />
                </x-menu-sub>
            @endif

            @if ($user?->hasPermission('academic'))
                <x-menu-sub title="Academic Structure" icon="fal.school-flag">
                    <x-menu-item title="Institutes" route="dashboard.academic.institutes" />
                    <x-menu-item title="Campuses" route="dashboard.academic.campuses" />
                    <x-menu-item title="Programmes" route="dashboard.academic.programmes.list" />
                    <x-menu-item title="Modules" route="dashboard.academic.modules.list" />
                    <x-menu-item title="Classes" route="dashboard.academic.classes.list" />
                </x-menu-sub>
            @endif

            @if ($user?->hasPermission('students'))
                <x-menu-sub title="Students" icon="fal.users">
                    <x-menu-item title="All Students" route="dashboard.students.list" />
                    <x-menu-item title="Create Student" route="dashboard.students.create" />
                    <x-menu-item title="Batch Import" link="/dashboard/students/import" />
                </x-menu-sub>
            @endif

            @if ($user?->hasPermission('activities'))
                <x-menu-sub title="Student Activities" icon="fal.calendar-star">
                    <x-menu-item title="All Activities" route="dashboard.activities.list" />
                    <x-menu-item title="Create Activities" route="dashboard.activities.create" />
                </x-menu-sub>
            @endif

            @if ($user?->hasPermission('news'))
                <x-menu-sub title="News & Announcement" icon="fal.newspaper">
                    <x-menu-item title="All Articles" route="dashboard.news.list" />
                    <x-menu-item title="Create Article" route="dashboard.news.create" />
                </x-menu-sub>
            @endif

            @if ($user?->hasPermission('resources'))
                <x-menu-sub title="Resource Centre" icon="fal.circle-info">
                    <x-menu-item title="All Resources" route="dashboard.resources.list" />
                    <x-menu-item title="Create Resource" route="dashboard.resources.create" />
                </x-menu-sub>
            @endif

            @if ($user?->hasRole('admin'))
                <x-menu-sub title="System Management" icon="fal.gear">
                    <x-menu-item title="Staff Members" route="dashboard.system.staff.list" />
                    <x-menu-item title="Password Reset" link="/dashboard/system/password" />
                </x-menu-sub>
            @endif
            
            <x-menu-separator />

            <x-menu-item title="Back to MyPortal" icon="fal.angles-left" route="portal.home" />
        </x-menu>
    </x-slot:sidebar>

    <x-slot:content>
        {{ $slot }}
    </x-slot:content>
</x-main>
@endsection
