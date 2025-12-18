<?php

use App\Traits\HasCssClassAttribute;
use Livewire\Component;

new class extends Component
{
    use HasCssClassAttribute;
}; ?>

<div class="brand font-bold text-primary dark:text-neutral-content text-2xl sm:ps-4 {{ $class }}">
    <a href="/" wire:navigate>
        <!-- <picture>
            <source srcset="/logo-dark.svg" media="(prefers-color-scheme: dark)" />
            <img src="/logo.svg" class="inline-block w-25 h-auto align-bottom" />
        </picture> -->
        <img src="/logo.svg" class="inline-block dark:hidden w-25 h-auto align-bottom" />
        <img src="/logo-dark.svg" class="hidden dark:inline-block w-25 h-auto align-bottom" />
        MyPortal
    </a>
</div>
