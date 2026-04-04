<?php

use App\Data\Assistant\Messages\AIMessage;
use App\Data\Assistant\Messages\Message;
use App\Data\Assistant\Messages\ToolMessage;
use Livewire\Component;

new class extends Component
{
    public Message $message;
    public ?string $time = null;

    public ?string $toolCall;
}; ?>

<div {{ $attributes->class(['chat', 'chat-start' => !$message->isHumanMessage(), 'chat-end' => $message->isHumanMessage()]) }}>
    <div class="chat-header">
        {{ $author ?? '' }}
        <time class="text-xs opacity-50">{{ $time ?? '' }}</time>
    </div>
    @if ($message instanceof ToolMessage)
        <div class="chat-footer">
            <x-collapse separator>
                <x-slot:heading class="flex items-center gap-2">
                    <span>Tool Result</span>
                    <x-badge
                        :value="$message->isSuccess() ? 'Success' : 'Error'"
                        @class(['badge-xs', 'badge-success' => $message->isSuccess(), 'badge-error' => !$message->isSuccess()])
                    />
                    @if ($message->toolCallId)
                        <span class="text-xs opacity-70">#{{ $message->toolCallId }}</span>
                    @endif
                </x-slot:heading>
                <x-slot:content class="prose prose-sm max-w-none" wire:stream.replace="{{ $message->id }}">
                    {!! $message->renderContent() !!}
                </x-slot:content>
            </x-collapse>
        </div>
    @else
        <div class="chat-bubble prose max-w-none empty:hidden" wire:stream.replace="{{ $message->id }}">{!! $message->renderContent() !!}</div>
        @if (($message instanceof AIMessage) && (count($message->toolCalls) || count($message->invalidToolCalls)))
            <div class="chat-footer">
                <x-accordion wire:model="toolCall">
                    @foreach ($message->toolCalls as $toolCall)
                        <x-collapse :name="$toolCall->id" separator>
                            <x-slot:heading>
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold">Tool: {{ $toolCall->name }}</span>
                                    @if ($toolCall->id)
                                        <span class="text-xs opacity-70">#{{ $toolCall->id }}</span>
                                    @endif
                                </div>
                            </x-slot:heading>

                            <x-slot:content>
                                <p class="mb-1 text-xs font-semibold uppercase tracking-wide opacity-60">Arguments</p>
                                <pre class="overflow-x-auto whitespace-pre-wrap wrap-break-word rounded-lg bg-base-200 p-3 text-xs" wire:stream.replace="{{ $toolCall->id }}">{{ $toolCall->renderArgs() }}</pre>
                            </x-slot:content>
                        </x-collapse>
                    @endforeach

                    @foreach ($message->invalidToolCalls as $toolCall)
                        <x-collapse :name="$toolCall->id" separator>
                            <x-slot:heading>
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold">Tool: {{ $toolCall->name }}</span>
                                    @if ($toolCall->id)
                                        <span class="text-xs opacity-70">#{{ $toolCall->id }}</span>
                                    @endif
                                </div>
                            </x-slot:heading>

                            <x-slot:content>
                                @if ($toolCall->error)
                                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-error">Error</p>
                                    <pre class="mb-3 overflow-x-auto whitespace-pre-wrap wrap-break-word rounded-lg bg-base-200 p-3 text-xs">{{ $toolCall->error }}</pre>
                                @endif

                                @if ($toolCall->args)
                                    <p class="mb-1 text-xs font-semibold uppercase tracking-wide opacity-60">Arguments</p>
                                    <pre class="overflow-x-auto whitespace-pre-wrap wrap-break-word rounded-lg bg-base-200 p-3 text-xs">{{ $toolCall->args }}</pre>
                                @endif
                            </x-slot:content>
                        </x-collapse>
                    @endforeach
                </x-accordion>
            </div>
        @endif
    @endif
</div>
