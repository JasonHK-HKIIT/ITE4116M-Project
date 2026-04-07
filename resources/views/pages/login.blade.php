<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new
#[Layout("layouts::empty")]
#[Title("Login")]
class extends Component
{
    public bool $appearance = false;

    #[Validate("required")]
    public string $username = "";

    #[Validate("required")]
    public string $password = "";

    public function mount()
    {
        if (Auth::user())
        {
            return redirect("/");
        }
    }

    public function login()
    {
        $credentials = $this->validate();

        if (Auth::attempt($credentials))
        {
            request()->session()->regenerate();
            return redirect()->intended("/");
        }

        $this->addError("username", __("The provided username/password was incorrect."));
    }
}; ?>

<div class="md:w-96 mx-auto mt-20">
    <div class="flex justify-between items-end mb-10">
        <livewire:brand />

        <x-button icon="fal.sliders" :tooltip-left="__('Appearance')" :aria-label="__('Appearance')" class="btn-square" @click="$wire.appearance = !$wire.appearance" />
    </div>

    <x-form wire:submit="login">
        <x-input :label="__('CNA / Student ID')" wire:model="username" type="text" autocomplete="username" icon="fal.user" :placeholder="__('CNA / Student ID')" inline />
        <x-input :label="__('Password')" wire:model="password" type="password" autocomplete="current-password" icon="fal.key" :placeholder="__('Password')" inline />

        <x-slot:actions>
            <x-button label="Create an account" class="btn-ghost" link="/register" />
            <x-button :label="__('Login')" type="submit" icon="fal.left-to-bracket" class="btn-primary" spinner="login" />
        </x-slot:actions>
    </x-form>

    <livewire:appearance wire:model="appearance" />
</div>
