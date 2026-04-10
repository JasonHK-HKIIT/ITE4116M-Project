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

            <x-menu-item :title="__('navigation.dashboard.home')" icon="fal.gauge-high" route="dashboard.home" />

            @if ($user?->hasPermission('calendar'))
                <x-menu-sub :title="__('navigation.dashboard.calendar')" icon="fal.calendar-users">
                    <x-menu-item :title="__('navigation.dashboard.all_events')" route="dashboard.calendar.manage" />
                    <x-menu-item :title="__('navigation.dashboard.create_event')" route="dashboard.calendar.events" />
                </x-menu-sub>
            @endif

            @if ($user?->hasPermission('academic'))
                <x-menu-sub :title="__('navigation.dashboard.academic_structure')" icon="fal.school-flag">
                    <x-menu-item :title="__('navigation.dashboard.institutes')" route="dashboard.academic.institutes" />
                    <x-menu-item :title="__('navigation.dashboard.campuses')" route="dashboard.academic.campuses" />
                    <x-menu-item :title="__('navigation.dashboard.programmes')" route="dashboard.academic.programmes.list" />
                    <x-menu-item :title="__('navigation.dashboard.modules')" route="dashboard.academic.modules.list" />
                    <x-menu-item :title="__('navigation.dashboard.classes')" route="dashboard.academic.classes.list" />
                </x-menu-sub>
            @endif

            @if ($user?->hasPermission('students'))
                <x-menu-sub :title="__('navigation.dashboard.students')" icon="fal.users">
                    <x-menu-item :title="__('navigation.dashboard.all_students')" route="dashboard.students.list" />
                    <x-menu-item :title="__('navigation.dashboard.create_student')" route="dashboard.students.create" />
                    <x-menu-item :title="__('navigation.dashboard.batch_import')" link="/dashboard/students/import" />
                </x-menu-sub>
            @endif

            @if ($user?->hasPermission('activities'))
                <x-menu-sub :title="__('navigation.dashboard.activities')" icon="fal.calendar-star">
                    <x-menu-item :title="__('navigation.dashboard.all_activities')" route="dashboard.activities.list" />
                    <x-menu-item :title="__('navigation.dashboard.create_activities')" route="dashboard.activities.create" />
                </x-menu-sub>
            @endif

            @if ($user?->hasPermission('news'))
                <x-menu-sub :title="__('navigation.dashboard.news')" icon="fal.newspaper">
                    <x-menu-item :title="__('navigation.dashboard.all_articles')" route="dashboard.news.list" />
                    <x-menu-item :title="__('navigation.dashboard.create_article')" route="dashboard.news.create" />
                </x-menu-sub>
            @endif

            @if ($user?->hasPermission('resources'))
                <x-menu-sub :title="__('navigation.dashboard.resource_centre')" icon="fal.circle-info">
                    <x-menu-item :title="__('navigation.dashboard.all_resources')" route="dashboard.resources.list" />
                    <x-menu-item :title="__('navigation.dashboard.create_resource')" route="dashboard.resources.create" />
                </x-menu-sub>
            @endif

            @if ($user?->hasRole('admin'))
                <x-menu-sub :title="__('navigation.dashboard.system_management')" icon="fal.gear">
                    <x-menu-item :title="__('navigation.dashboard.staff_members')" route="dashboard.system.staff.list" />
                    <x-menu-item :title="__('navigation.dashboard.password_reset')" link="/dashboard/system/password" />
                </x-menu-sub>
            @endif
            
            <x-menu-separator />

            <x-menu-item :title="__('navigation.dashboard.back_to_portal')" icon="fal.angles-left" route="portal.home" />
        </x-menu>
    </x-slot:sidebar>

    <x-slot:content>
        {{ $slot }}
    </x-slot:content>
</x-main>
@endsection
