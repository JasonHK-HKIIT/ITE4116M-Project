<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout("layouts::assistant")]
class extends Component
{
    public ?string $message = null;
}; ?>

<x-main full-width class="grow-1 flex flex-col" drawer-class="grow-1">
    <x-slot:sidebar drawer="main-drawer" class="bg-base-100 lg:bg-inherit">
        <x-brand class="px-5 pt-4" />

        <x-menu activate-by-route>
            <x-sidebar-user />

            <x-menu-item title="New Chat" icon="fal.message-plus" route="portal.assistant" />
            <x-menu-sub title="Chat History" icon="fal.clock-rotate-left" open>
                <x-menu-item title="Chat A" :link="route('portal.assistant.history', ['id' => 'f81d4fae-7dec-11d0-a765-00a0c91e6bf6'])" />
                <x-menu-item title="Chat B" :link="route('portal.assistant.history', ['id' => 'f81d4fae-7dec-11d0-a765-00a0c91e6bf7'])" />
            </x-menu-sub>
            
            <x-menu-separator />

            <x-menu-item title="Back to MyPortal" icon="fal.angles-left" route="portal.home" />
        </x-menu>
    </x-slot:sidebar>

    <x-slot:content class="flex flex-col">
        <x-header title="New Chat" separator class="!mb-0" />
        
        <div class="grow-1 self-center flex flex-col w-full max-w-192">
            <div class="grow-1">

            </div>

            <x-form no-separator>
                <x-textarea wire:model="message" placeholder="Ask anything" rows="3" class="resize-none" />

                <x-slot:actions>
                    <x-button label="Send" icon-right="fal.paper-plane-top" type="submit" class="btn-primary" spinner />
                </x-slot:actions>
            </x-form>
        </div>
    </x-slot:content>
</x-main>
