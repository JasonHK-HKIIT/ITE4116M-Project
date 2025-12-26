@extends("layouts::base")

@section("content")
<x-nav sticky class="lg:hidden">
    <x-slot:brand>
        <livewire:brand />
    </x-slot:brand>
    <x-slot:actions>
        <label for="main-drawer" class="lg:hidden me-3">
            <x-icon name="o-bars-3" class="cursor-pointer" />
        </label>
    </x-slot:actions>
</x-nav>

<x-main>
    <x-slot:sidebar drawer="main-drawer" class="bg-base-100 lg:bg-inherit">
        <livewire:brand class="px-5 pt-4" />

        <x-menu activate-by-route>
            @if($user = auth()->user())
                <x-menu-separator />

                <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="-mx-2 !-my-2 rounded">
                    <x-slot:actions>
                        <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="Sign Out" no-wire-navigate link="/logout" />
                    </x-slot:actions>
                </x-list-item>

                <x-menu-separator />
            @endif

            <x-button label="Chat with Valo" link="/assistant" icon="o-sparkles" class="btn-primary btn-block mb-2" />
            <x-menu-item title="Home" icon="o-home" link="/" />
            <x-menu-item title="Calendar" icon="o-calendar-days" link="/calendar" />
            <x-menu-item title="Profile" icon="o-user-circle" link="/profile" />
            <x-menu-item title="Student Activities" icon="o-sparkles" link="/activities" />
            <x-menu-item title="News and Announcement" icon="o-newspaper" link="/news" />
        </x-menu>
    </x-slot:sidebar>

    <x-slot:content>
        {{ $slot }}
    </x-slot:content>
</x-main>
@endsection
