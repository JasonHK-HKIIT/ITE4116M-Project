<?php

use Livewire\Component;

new class extends Component
{
    //
}; ?>

<div>
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
</div>
