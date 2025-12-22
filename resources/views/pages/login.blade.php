<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout("layouts::empty")]
#[Title("Login")]
class extends Component
{
    #[Rule("required")]
    public string $username = "";

    #[Rule("required")]
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

        $this->addError("username", "The provided username/password was incorrect.");
    }
}; ?>

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
