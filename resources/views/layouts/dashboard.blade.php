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
            @if($user = auth()->user())
                <x-menu-separator />

                <x-list-item :item="$user" value="given_name" sub-value="username" no-separator no-hover class="-mx-2 !-my-2 rounded">
                    <x-slot:avatar>
                        <div>
                            <div class="avatar">
                                <div class="w-11 rounded-full">
                                    <img src="{{ $user->avatar() }}" />
                                </div>
                            </div>
                        </div>
                    </x-slot:avatar>
                    <x-slot:actions>
                        <x-button icon="fal.right-from-bracket" class="btn-circle btn-ghost btn-xs" tooltip-left="Sign Out" no-wire-navigate link="/logout" />
                    </x-slot:actions>
                </x-list-item>

                <x-menu-separator />
            @endif

            <x-menu-item title="Dashboard" icon="fal.gauge-high" route="dashboard.home" />
            <x-menu-item title="Calendar" icon="fal.calendar-circle-user" link="/calendar" />
            <x-menu-sub title="Profile" icon="fal.address-card">
                <x-menu-item title="Personal Particular" link="/profile/personal-particular" />
                <x-menu-item title="Programme & Modules" link="/profile/programme-modules" />
            </x-menu-sub>
            <x-menu-item title="Student Activities" icon="fal.calendar-star" link="/activities" />

            <x-menu-sub title="News & Announcement" icon="fal.newspaper">
                <x-menu-item title="All Articles" route="dashboard.news.list" />
                <x-menu-item title="Create Article" route="dashboard.news.create" />
            </x-menu-sub>

            <x-menu-item title="Resource Centre" icon="fal.circle-info" link="/resources" />
            
            <x-menu-separator />

            <x-menu-item title="Back to MyPortal" icon="fal.person-to-portal" route="portal.home" />
        </x-menu>
    </x-slot:sidebar>

    <x-slot:content>
        {{ $slot }}
    </x-slot:content>
</x-main>
@endsection
