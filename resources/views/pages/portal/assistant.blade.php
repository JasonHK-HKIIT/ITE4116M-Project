<?php

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Symfony\Component\HttpClient\Chunk\ServerSentEvent;
use Symfony\Component\HttpClient\EventSourceHttpClient;
use Symfony\Component\HttpClient\HttpClient;

new
#[Layout("layouts::assistant")]
class extends Component
{
    #[Locked]
    public array $threads = [];

    #[Locked]
    public ?string $threadId = null;

    #[Locked]
    public array $messages = [];

    #[Locked]
    public ?string $lastMessageId = null;

    #[Validate('required')]
    public ?string $question = null;

    public function mount(?string $id = null): void
    {
        try
        {
            $response = Http::get(config('app.assistant.base_url') . '/health');
            abort_unless($response->ok(), 503);
        }
        catch (ConnectionException $ex)
        {
            abort(503);
        }

        $this->threadId = $id;

        if ($this->threadId)
        {
            /** @var \Illuminate\Http\Client\Response */
            $response = Http::withCookies(['opengpts_user_id' => auth()->user()->id], 'localhost')->get(config('app.assistant.base_url') . "/threads/{$this->threadId}/state");
            if (!$response->ok())
            {
                Log::error("OpenGPTs error: {$response->json('detail')}", ['Thread ID' => $this->threadId]);
                abort($response->status());
            }

            $state = $response->json();
            $this->messages = $state['values'];
            $this->lastMessageId = array_last($this->messages)['id'];
        }

        $this->refreshThreads(true);
    }

    #[On('refresh-threads')]
    public function refreshThreads(bool $initial = false): void
    {
        /** @var \Illuminate\Http\Client\Response */
        $response = Http::withCookies(['opengpts_user_id' => auth()->user()->id], 'localhost')->get(config('app.assistant.base_url') . '/threads/');
        $threads = $response->json();
        usort($threads, fn($a, $b) => (strtotime($b['updated_at']) - strtotime($a['updated_at'])));
        $this->threads = $threads;

        if (!$initial) { $this->renderIsland('threads', with: $this); }
    }

    public function send(): void
    {
        $this->validateOnly('question');
        $question = $this->question;
        $this->question = null;

        if (!$this->threadId)
        {
            /** @var \Illuminate\Http\Client\Response */
            $response = Http::withCookies(['opengpts_user_id' => auth()->user()->id], 'localhost')->post(config('app.assistant.base_url') . '/assistants',
                [
                    'public' => false,
                    'name' => '',
                    'config' => [
                        'configurable' => [
                            'type' => 'agent',
                            'type==agent/agent_type' => 'GPT 3.5 Turbo',
                            'type==agent/system_message' => 'You are a helpful assistant.',
                            'type==agent/retrieval_description' => 'Can be used to look up information that was uploaded to this assistant.
    If the user is referencing particular files, that is often a good hint that information may be here.
    If the user asks a vague question, they are likely meaning to look up info from this retriever, and you should call it!',
                            'type==agent/tools' => [],
                            'type==agent/interrupt_before_action' => false,
                        ],
                    ],
                ]);
            $assistant = $response->json();

            /** @var \Illuminate\Http\Client\Response */
            $response = Http::withCookies(['opengpts_user_id' => auth()->user()->id], 'localhost')->post(config('app.assistant.base_url') . '/threads',
                [
                    'assistant_id' => $assistant['assistant_id'],
                    'name' => $question,
                ]);
            $thread = $response->json();
            $this->threadId = $thread['thread_id'];

            $this->dispatch('thread-created', url: route('portal.assistant.thread', ['id' => $this->threadId], false))->self();
            $this->dispatch('refresh-threads')->self();
        }
        
        $this->dispatch('question-sent', question: $question)->self();
    }

    public function think(string $question): void
    {
        $client = new EventSourceHttpClient(HttpClient::create(
            [
                'headers' => [
                    'Cookie' => sprintf("%s=%s", 'opengpts_user_id', rawurlencode(auth()->user()->id))
                ],
            ]));

        $source = $client->connect(config('app.assistant.base_url') . '/runs/stream',
            [
                'json' => [
                    'thread_id' => $this->threadId,
                    'input' => [
                        [
                            'type' => 'human',
                            'content' => $question,
                        ],
                    ],
                ],
            ], 'POST');

        while ($source)
        {
            foreach ($client->stream($source) as $event)
            {
                if ($event->isLast())
                {
                    $this->dispatch('refresh-threads')->self();
                    return;
                }

                if (($event instanceof ServerSentEvent) && ($event->getType() == 'data'))
                {
                    $this->messages = $event->getArrayData();
                    $message = array_last($event->getArrayData());
                    if ($message['id'] != $this->lastMessageId)
                    {
                        $this->lastMessageId = $message['id'];
                        $this->streamIsland('messages', mode: 'append', with: $this);
                    }

                    $this->stream(to: $message['id'], content: $message['content'], replace: true);
                    usleep(50000);
                }
            }
        }
    }
}; ?>

@script
    <script>
        this.$on("question-sent", ({ question }) => $wire.think(question));
        this.$on("thread-created", ({ url }) => history.replaceState(history.state, "", url));
    </script>
@endscript

<x-main full-width class="grow-1 flex flex-col" drawer-class="grow-1">

    <x-slot:sidebar drawer="main-drawer" class="bg-base-100 lg:bg-inherit">
        <livewire:brand class="px-5 pt-4" />

        <x-menu activate-by-route>
            <x-sidebar-user />

            <x-menu-item title="New Chat" icon="fal.message-plus" route="portal.assistant" />
            <x-menu-sub title="Chat History" icon="fal.clock-rotate-left" open>
                @island(name: 'threads')
                    @foreach ($threads as $thread)
                        <x-menu-item wire:key="{{ $thread['thread_id'] }}" :title="$thread['name']" :link="route('portal.assistant.thread', ['id' => $thread['thread_id']])" />
                    @endforeach
                @endisland
            </x-menu-sub>
            
            <x-menu-separator />

            <x-menu-item title="Back to MyPortal" icon="fal.angles-left" route="portal.home" />
        </x-menu>
    </x-slot:sidebar>

    <x-slot:content class="flex flex-col">
        <x-header title="New Chat" separator class="!mb-0" />
        
        <div class="grow-1 flex flex-col">
            <div class="pt-10 grow-1 flex flex-col overflow-y-auto">
                <div class="self-center w-full max-w-192" style="container-type: size;">
                    @island(name: 'messages')
                        @foreach ($messages as $message)
                            <livewire:chat-bubble wire:key="{{ @$message['id'] }}" :sent="$message['type'] == 'human'" wire:stream="{{ $message['id'] }}">
                                {{ $message['content'] }}
                            </livewire:chat-bubble>
                        @endforeach
                    @endisland
                    <div class="h-5"></div>
                </div>
            </div>

            <x-form wire:submit="send" no-separator class="self-center w-full max-w-192">
                <x-textarea wire:model="question" placeholder="Ask anything" rows="3" class="resize-none" autofocus />

                <x-slot:actions>
                    <x-button label="Send" icon-right="fal.paper-plane-top" type="submit" class="btn-primary" spinner="send, think" />
                </x-slot:actions>
            </x-form>
        </div>
    </x-slot:content>
</x-main>
