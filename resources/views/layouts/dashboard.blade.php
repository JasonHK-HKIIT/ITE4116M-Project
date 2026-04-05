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

        <x-menu activate-by-route>
            <x-sidebar-user />

            <x-menu-item title="Dashboard" icon="fal.gauge-high" route="dashboard.home" />

            <x-menu-sub title="Calendar" icon="fal.calendar-circle-user">
                <x-menu-item title="Add Events" route="dashboard.calendar.events" />
                <x-menu-item title="Manage Events" route="dashboard.calendar.manage" />
            </x-menu-sub>

            <x-menu-sub title="Academic Structure" icon="fal.school-flag">
                <x-menu-item title="Institutes" route="dashboard.academic.institutes" />
                <x-menu-item title="Campuses" route="dashboard.academic.campuses" />
                <x-menu-item title="Programmes" route="dashboard.academic.programmes.list" />
                <x-menu-item title="Modules" route="dashboard.academic.modules.list" />
                <x-menu-item title="Classes" route="dashboard.academic.classes.list" />
            </x-menu-sub>

            <x-menu-sub title="Students" icon="fal.users">
                <x-menu-item title="All Students" route="dashboard.students.list" />
                <x-menu-item title="Create Student" route="dashboard.students.create" />
                <x-menu-item title="Batch Import" link="/dashboard/students/import" />
            </x-menu-sub>

            
            <x-menu-sub title="Student Activities" icon="fal.calendar-star">
                <x-menu-item title="All Activities" route="dashboard.activities.list" />
                <x-menu-item title="Create Activities" route="dashboard.activities.create" />
            </x-menu-sub>
            

            <x-menu-sub title="News & Announcement" icon="fal.newspaper">
                <x-menu-item title="All Articles" route="dashboard.news.list" />
                <x-menu-item title="Create Article" route="dashboard.news.create" />
            </x-menu-sub>

            <x-menu-sub title="Resource Centre" icon="fal.circle-info">
                <x-menu-item title="All Resources" route="dashboard.resources.list" />
                <x-menu-item title="Create Resource" route="dashboard.resources.create" />
            </x-menu-sub>
            
            <x-menu-separator />

            <x-menu-item title="Back to MyPortal" icon="fal.angles-left" route="portal.home" />
        </x-menu>
    </x-slot:sidebar>

    <x-slot:content>
        {{ $slot }}
    </x-slot:content>
</x-main>
@endsection
