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
        <x-brand class="px-5 pt-4" />

        <x-menu activate-by-route>
            <x-sidebar-user />

            <x-button label="Chat with Valo" link="/assistant" icon="fal.sparkles" class="btn-primary btn-block mt-0.5 mb-1" />
            <x-menu-item title="Home" icon="fal.house" link="/" />
            <x-menu-item title="Calendar" icon="fal.calendar-circle-user" link="/calendar" />
            <x-menu-sub title="Profile" icon="fal.address-card">
                <x-menu-item title="Personal Particular" link="/profile/personal-particular" />
                <x-menu-item title="Programme & Modules" link="/profile/programme-modules" />
            </x-menu-sub>
            <x-menu-item title="Student Activities" icon="fal.calendar-star" link="/activities" />
            <x-menu-item title="News & Announcement" icon="fal.newspaper" link="/news" />
            <x-menu-item title="Resources Centre" icon="fal.circle-info" link="/resources/resources-centre" />
            
            <x-menu-separator />

            <x-menu-item title="Dashboard" icon="fal.gauge-high" link="/dashboard" />
        </x-menu>
    </x-slot:sidebar>

    <x-slot:content>
        {{ $slot }}
    </x-slot:content>
</x-main>
@endsection
