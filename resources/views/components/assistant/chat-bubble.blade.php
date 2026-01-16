<?php

use Livewire\Component;

new class extends Component
{
    
}; ?>

<div @class(['chat', 'chat-start' => true, 'chat-end' => true])>
    <div class="chat-header">
        {{ $author ?? '' }}
        <time class="text-xs opacity-50">{{ $time }}</time>
    </div>
    <div class="chat-bubble">{{ $message }}</div>
</div>
