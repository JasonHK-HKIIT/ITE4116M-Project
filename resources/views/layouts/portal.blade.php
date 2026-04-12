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

            <x-button :label="__('navigation.portal.chat_with_valo')" link="{{ route('portal.assistant') }}" icon="fal.sparkles" class="btn-primary btn-block mt-0.5 mb-1" />

            <x-menu-item :title="__('navigation.portal.home')" icon="fal.house" link="/" />
            <x-menu-item :title="__('navigation.portal.calendar')" icon="fal.calendar-circle-user" link="/calendar" />
            @if($user?->hasRole('student'))
                <x-menu-sub :title="__('navigation.portal.profile')" icon="fal.address-card">
                    <x-menu-item :title="__('navigation.portal.personal_particular')" link="/profile/personal-particular" />
                    <x-menu-item :title="__('navigation.portal.programme_modules')" link="/profile/programme-modules" />
                </x-menu-sub>
                <x-menu-item :title="__('navigation.portal.activities')" icon="fal.calendar-star" link="/activities" />
            @endif
            <x-menu-item :title="__('navigation.portal.news')" icon="fal.newspaper" link="/news" />
            <x-menu-item :title="__('navigation.portal.resources')" icon="fal.circle-info" link="/resources/resources-centre" />

            @if($user?->hasAnyRole('admin', 'staff'))
                <x-menu-separator />
                
                <x-menu-item :title="__('navigation.portal.dashboard')" icon="fal.gauge-high" link="/dashboard" />
            @endif
        </x-menu>
    </x-slot:sidebar>

    <x-slot:content>
        {{ $slot }}
    </x-slot:content>
</x-main>
@endsection
