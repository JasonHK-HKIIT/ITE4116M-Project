<?php

use App\Data\Assistant\AssistantCreatePayload;
use App\Data\Assistant\ExecutionCreatePayload;
use App\Data\Assistant\Messages\AIMessage;
use App\Data\Assistant\Messages\Message;
use App\Data\Assistant\Thread;
use App\Data\Assistant\ThreadCreatePayload;
use App\Services\AssistantClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;
use Symfony\Component\HttpClient\Chunk\ServerSentEvent;

new
#[Layout("layouts::assistant")]
class extends Component
{
    use Toast;

    #[Locked]
    public array $threads = [];

    #[Locked]
    public ?string $threadId = null;

    #[Locked]
    public ?Thread $thread = null;

    /** @var Message[] */
    #[Locked]
    public array $messages = [];

    #[Locked]
    public ?string $lastMessageId = null;

    #[Validate('required')]
    public ?string $question = null;

    private AssistantClient $client;

    public function boot(AssistantClient $client): void
    {
        $this->client = $client;
        abort_unless($this->client->isHealthy(), 503);
    }

    public function mount(?string $id = null): void
    {
        $this->threadId = $id;
        if ($this->threadId)
        {            
            $this->thread = $this->client->getThread($this->threadId);

            $state = $this->client->getThreadState($this->threadId);
            $this->messages = $state->values ?? [];
            $this->lastMessageId = array_last($this->messages)?->id ?? null;
        }

        $this->refreshThreads(true);
    }

    #[On('refresh-threads')]
    public function refreshThreads(bool $initial = false): void
    {
        $threads = $this->client->listThreads();
        usort($threads, fn($a, $b) => ($b->updatedAt->diff($a->updatedAt)->f));
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
            $assistant = $this->client->createAssistant(AssistantCreatePayload::from(
                name: '',
                config: [
                    'configurable' => [
                        'type' => 'agent',
                        'type==agent/agent_type' => 'GPT 3.5 Turbo',
                        'type==agent/system_message' => Storage::disk('local')->get('assistant/system_message.txt'),
                        'type==agent/retrieval_description' => 'Can be used to look up information that was uploaded to this assistant.
If the user is referencing particular files, that is often a good hint that information may be here.
If the user asks a vague question, they are likely meaning to look up info from this retriever, and you should call it!',
                        'type==agent/tools' => [
                            ['type' => 'news_articles'],
                            ['type' => 'student_activities'],
                            [
                                'type' => 'user_profile',
                                'config' => [
                                    'user_id' => (string) Auth::id(),
                                ],
                            ],
                        ],
                        'type==agent/interrupt_before_action' => false,
                    ],
                ]));

            $this->thread = $this->client->createThread(ThreadCreatePayload::from($question, $assistant->assistantId));
            $this->threadId = $this->thread->threadId;

            $this->dispatch('thread-created', url: route('portal.assistant.thread', ['id' => $this->threadId], false))->self();
            $this->dispatch('refresh-threads')->self();
        }
        
        $this->dispatch('question-sent', question: $question)->self();
    }

    public function delete(): void
    {
        if ($this->client->deleteThread($this->threadId))
        {
            $this->success('Thread deleted.', redirectTo: route('portal.assistant'));
        }
    }

    public function think(string $question): void
    {
        $response = $this->client->streamExecution(ExecutionCreatePayload::from(
            threadId: $this->threadId,
            input: [
                [
                    'type' => 'human',
                    'content' => $question,
                ],
            ]));

        while ($response->source)
        {
            foreach ($response->client->stream($response->source) as $event)
            {
                if ($event->isLast())
                {
                    $this->dispatch('refresh-threads')->self();
                    $this->dispatch('question-answered')->self();
                    return;
                }

                if (($event instanceof ServerSentEvent) && ($event->getType() == 'data'))
                {
                    $this->messages = Message::collect($event->getArrayData(), 'array');
                    $message = array_last($this->messages);
                    if ($message?->id != $this->lastMessageId)
                    {
                        $this->lastMessageId = $message->id;
                        $this->streamIsland('messages', mode: 'append', with: [ 'messages' => [$message] ]);
                    }

                    if ($message instanceof AIMessage)
                    {
                        foreach ($message->toolCalls as $toolCall)
                        {
                            $this->stream(to: $toolCall->id, content: e($toolCall->renderArgs()));
                        }
                    }

                    $this->stream(to: $message->id, content: $message->renderContent());
                    usleep(50000);
                }
            }
        }
    }
}; ?>

@script
    <script>
        const messagesContainer = document.getElementById("messages-container");

        const messagesEndMarker = document.getElementById("messages-end");
        messagesEndMarker.scrollIntoView(false);

        let firstChatBubble = true;
        const observer = new MutationObserver((records) =>
        {
            const scrollFromTop = messagesContainer.scrollHeight - (messagesContainer.scrollTop + messagesContainer.clientHeight);

            for (const record of records)
            {
                const nodes = Array.from(record.addedNodes);
                if (nodes.some((node) => ((node instanceof HTMLElement) && node.classList.contains("chat"))))
                {
                    if (firstChatBubble || (scrollFromTop <= 64))
                    {
                        firstChatBubble = false;
                        messagesEndMarker.scrollIntoView(false);
                    }
                }
                else if (nodes.some((node) => ((node instanceof HTMLElement) ? node : node.parentElement)?.closest(".chat-bubble")))
                {
                    if (scrollFromTop <= 24)
                    {
                        messagesEndMarker.scrollIntoView(false);
                    }
                }
            }
        });

        this.$on("thread-created", ({ url }) => history.replaceState(history.state, "", url));

        this.$on("question-sent", ({ question }) =>
        {
            firstChatBubble = true;
            observer.observe(messagesEndMarker.parentElement, { childList: true, subtree: true });

            $wire.think(question);
        });

        this.$on("question-answered", () => observer.disconnect());
    </script>
@endscript

<x-main full-width class="grow-1 flex flex-col" drawer-class="grow-1">

    <x-slot:sidebar drawer="main-drawer" class="bg-base-100 lg:bg-inherit">
        <livewire:brand class="px-5 pt-4" />

        <x-menu activate-by-route>
            <livewire:sidebar-user />

            <x-menu-item title="New Chat" icon="fal.message-plus" route="portal.assistant" />
            <x-menu-sub title="Chat History" icon="fal.clock-rotate-left" open>
                @island(name: 'threads')
                    @foreach ($threads as $thread)
                        <x-menu-item wire:key="{{ $thread->threadId }}" :title="$thread->name" :link="route('portal.assistant.thread', ['id' => $thread->threadId])" />
                    @endforeach
                @endisland
            </x-menu-sub>
            
            <x-menu-separator />

            <x-menu-item title="Back to MyPortal" icon="fal.angles-left" route="portal.home" />
        </x-menu>
    </x-slot:sidebar>

    <x-slot:content class="flex flex-col">
        <x-header :title="$threadId ? 'Chat Thread' : 'New Chat'" :subtitle="$thread?->name" separator class="!mb-0">
            <x-slot:actions>
                @if ($thread)
                    <x-button :label="__('Delete Thread')" icon="fal.trash" class="btn-primary" wire:click="delete()" loading />
                @endif
            </x-slot:actions>
        </x-header>
        
        <div class="grow-1 flex flex-col">
            <div id="messages-container" class="pt-10 grow-1 flex flex-col overflow-y-auto" style="scrollbar-width: none;">
                <div class="self-center w-full max-w-192" style="container-type: size;">
                    @island(name: 'messages')
                        @foreach ($messages as $message)
                            <livewire:chat-message id="{{ $message->id }}" wire:key="{{ $message->id }}" :message="$message" />
                        @endforeach
                    @endisland
                    <div id="messages-end" class="h-5"></div>
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
