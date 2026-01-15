<?php

use App\Traits\HasCssClassAttribute;
use Livewire\Component;

new class extends Component
{
    use HasCssClassAttribute;
}; ?>

<div class="brand font-bold text-primary dark:text-neutral-content text-2xl sm:ps-4 select-none {{ $class ?? '' }}">
    @svg('logo', 'inline-block w-25 h-17 align-bottom', ['title' => 'VTC'])
    MyPortal
</div>
