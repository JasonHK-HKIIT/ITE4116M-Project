<?php

use App\Traits\HasCssClassAttribute;
use Livewire\Component;

new class extends Component
{
    use HasCssClassAttribute;
}; ?>

<div class="brand font-bold text-primary dark:text-neutral-content text-2xl sm:ps-4 select-none {{ $class }}">
    <img src="/logo.svg" alt="VTC" class="inline-block dark:hidden w-25 h-17 align-bottom" />
    <img src="/logo-dark.svg" alt="VTC" class="hidden dark:inline-block w-25 h-17 align-bottom" />
    MyPortal
</div>
