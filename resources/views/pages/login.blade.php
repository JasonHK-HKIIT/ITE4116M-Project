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
        <x-brand />

        <x-dropdown right>
            <x-slot:trigger>
                <x-button icon="fal.language" :tooltip-left="__('Language')" :aria-label="__('Language')" class="btn-square" />
            </x-slot:trigger>

            <x-menu-item title="English" lang="en" />
            <x-menu-item title="中文（繁體）" lang="zh-HK" />
            <x-menu-item title="中文（简体）" lang="zh-CN" />
        </x-dropdown>
    </div>

    <x-form wire:submit="login">
        <x-input :label="__('CNA / Student ID')" wire:model="username" type="text" autocomplete="username" icon="fal.user" :placeholder="__('CNA / Student ID')" inline />
        <x-input :label="__('Password')" wire:model="password" type="password" autocomplete="current-password" icon="fal.key" :placeholder="__('Password')" inline />

        <x-slot:actions>
            <x-button label="Create an account" class="btn-ghost" link="/register" />
            <x-button :label="__('Login')" type="submit" icon="fal.left-to-bracket" class="btn-primary" spinner="login" />
        </x-slot:actions>
    </x-form>
</div>
