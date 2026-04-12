<?php

use Livewire\Component;

new class extends Component
{
    public bool $appearance = false;
}; ?>

<div>
    @if($user = auth()->user())
        <x-menu-separator />

        <x-list-item :item="$user" value="given_name" sub-value="username" no-separator no-hover class="-mx-2 !-my-2 rounded">
            <x-slot:actions>
                <x-dropdown right>
                    <x-slot:trigger>
                        <x-button icon="fal.circle-chevron-down" :tooltip-left="__('actions.quick_menu')" :aria-label="__('actions.quick_menu')" class="btn-circle btn-ghost btn-xs" />
                    </x-slot:trigger>

                    <x-menu-item :title="__('appearance.title')" @click="$wire.appearance = !$wire.appearance" />
                    <x-menu-item :title="__('actions.change_password')" route="password" />
                    <x-menu-item :title="__('actions.sign_out')" route="logout" />
                </x-dropdown>
            </x-slot:actions>
        </x-list-item>

        <x-menu-separator />
    @endif

    <livewire:appearance wire:model="appearance" />
</div>
