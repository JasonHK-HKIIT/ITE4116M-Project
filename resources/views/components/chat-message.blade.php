<?php

use App\Data\Assistant\Messages\Message;
use App\Enums\Assistant\MessageType;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public Message $message;
    public ?string $time = null;

    public function with(): array
    {
        return [
            'isHumanMessage' => $this->isHumanMessage(),
        ];
    }

    #[Computed]
    public function isHumanMessage(): bool
    {
        return ($this->message->type == MessageType::Human);
    }
}; ?>

<div {{ $attributes->whereDoesntStartWith('wire:stream')->class(['chat', 'chat-start' => !$isHumanMessage, 'chat-end' => $isHumanMessage]) }}>
    <div class="chat-header">
        {{ $author ?? '' }}
        <time class="text-xs opacity-50">{{ $time ?? '' }}</time>
    </div>
    <div {{ $attributes->whereStartsWith('wire:stream')->class('chat-bubble') }}>{{ $message->content }}</div>
</div>
