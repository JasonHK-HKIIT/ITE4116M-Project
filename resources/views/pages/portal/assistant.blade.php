<?php

use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Symfony\Component\HttpClient\Chunk\ServerSentEvent;
use Symfony\Component\HttpClient\EventSourceHttpClient;
use Symfony\Component\HttpClient\HttpClient;

new
#[Layout("layouts::assistant")]
class extends Component
{
    public ?string $threadId = null;

    public array $records = [];

    public ?string $runId = null;

    public ?string $message = null;
    public ?string $question = null;

    public function send(): void
    {
        $this->question = $this->message;
        $this->message = null;

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
                    'name' => $this->question,
                ]);
            $thread = $response->json();
            $this->threadId = $thread['thread_id'];

            $this->dispatch('chat-created', url: route('portal.assistant.history', ['id' => $this->threadId], false));
        }
        
        // /** @var \Illuminate\Http\Client\Response */
        // $response = Http::withCookies(['opengpts_user_id' => auth()->user()->id], 'localhost')->post(config('app.assistant.base_url') . '/threads/' . $this->threadId . '/state',
        //     [
        //         'values' => [
        //             [
        //                 'type' => 'human',
        //                 'content' => $message,
        //             ],
        //         ],
        //     ]);

        // /** @var \Illuminate\Http\Client\Response */
        // $response = Http::withCookies(['opengpts_user_id' => auth()->user()->id], 'localhost')->get(config('app.assistant.base_url') . '/threads/' . $this->threadId . '/state');
        // $state = $response->json();
        // $this->records = $state['values'];
        // $this->runId = "Hi";
        
        $this->js('$wire.think()');
    }

    public function think(): void
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
                            'content' => $this->question,
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
                    /** @var \Illuminate\Http\Client\Response */
                    $response = Http::withCookies(['opengpts_user_id' => auth()->user()->id], 'localhost')->get(config('app.assistant.base_url') . '/threads/' . $this->threadId . '/state');
                    $state = $response->json();
                    $this->records = $state['values'];
                    $this->question = null;

                    return;
                }

                if ($event instanceof ServerSentEvent)
                {
                    switch ($event->getType())
                    {
                        case 'metadata':
                            $this->runId = json_decode($event->getData())->run_id;
                            // $this->render
                            break;
                        case 'data':
                            $message = last(json_decode($event->getData()));
                            if ($message->type != 'human')
                            {
                                $this->stream(to: 'message', content: $message->content, replace: true);
                                usleep(100000);
                            }
                            break;
                    }
                }
            }
        }
    }
}; ?>

<x-main full-width class="grow-1 flex flex-col" drawer-class="grow-1">
    @script
        <script>
            this.$on("chat-created", ({ url }) => history.replaceState(history.state, "", url));
        </script>
    @endscript

    <x-slot:sidebar drawer="main-drawer" class="bg-base-100 lg:bg-inherit">
        <livewire:brand class="px-5 pt-4" />

        <x-menu activate-by-route>
            <x-sidebar-user />

            <x-menu-item title="New Chat" icon="fal.message-plus" route="portal.assistant" />
            <x-menu-sub title="Chat History" icon="fal.clock-rotate-left" open>
                <x-menu-item title="Chat A" :link="route('portal.assistant.history', ['id' => 'f81d4fae-7dec-11d0-a765-00a0c91e6bf6'])" />
                <x-menu-item title="Chat B" :link="route('portal.assistant.history', ['id' => 'f81d4fae-7dec-11d0-a765-00a0c91e6bf7'])" />
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
                    @foreach ($records as $record)
                        <livewire:chat-bubble :sent="$record['type'] == 'human'" wire:stream="{{ $record['id'] }}">
                            {{ $record['content'] }}
                        </livewire:chat-bubble>
                    @endforeach
                    @if ($question)
                        <livewire:chat-bubble sent>
                            {{ $question }}
                        </livewire:chat-bubble>
                        <livewire:chat-bubble author="Valo" wire:stream="message"></livewire:chat-bubble>
                    @endif
                    <div class="h-5"></div>
                </div>
            </div>

            <x-form wire:submit="send" no-separator class="self-center w-full max-w-192">
                <x-textarea wire:model="message" placeholder="Ask anything" rows="3" class="resize-none" autofocus />

                <x-slot:actions>
                    <x-button label="Send" icon-right="fal.paper-plane-top" type="submit" class="btn-primary" spinner="send, think" />
                </x-slot:actions>
            </x-form>
        </div>
    </x-slot:content>
</x-main>
