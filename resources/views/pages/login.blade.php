<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout("layouts::empty")]
#[Title("Login")]
class extends Component
{
    //
};
?>

<div class="md:w-96 mx-auto mt-20">
    <div class="mb-10">
        <livewire:brand />
    </div>

    <x-form wire:submit="login">
        <x-input placeholder="CNA / Student ID" wire:model="username" icon="o-envelope" />
        <x-input placeholder="Password" wire:model="password" type="password" icon="o-key" />

        <x-slot:actions>
            <x-button label="Create an account" class="btn-ghost" link="/register" />
            <x-button label="Login" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="login" />
        </x-slot:actions>
    </x-form>
</div>
