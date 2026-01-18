<?php

use Livewire\Component;

new class extends Component
{
    public ?string $author = null;
    public ?string $time = null;
    public bool $sent = false;
}; ?>

<div {{ $attributes->whereDoesntStartWith('wire:stream')->class(['chat', 'chat-start' => !$sent, 'chat-end' => $sent]) }}>
    <div class="chat-header">
        {{ $author ?? '' }}
        <time class="text-xs opacity-50">{{ $time ?? '' }}</time>
    </div>
    <div {{ $attributes->whereStartsWith('wire:stream')->class('chat-bubble') }}>{{ $slot }}</div>
</div>
