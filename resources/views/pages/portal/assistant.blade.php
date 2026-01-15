<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout("layouts::assistant")]
class extends Component
{
    public ?string $message = null;
}; ?>

<x-main full-width class="grow-1 flex flex-col" drawer-class="grow-1">
    <x-slot:sidebar drawer="main-drawer" class="bg-base-100 lg:bg-inherit">
        <x-brand class="px-5 pt-4" />

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
        
        <div class="grow-1 self-center flex flex-col w-full max-w-192">
            <div class="pt-10 grow-1 overflow-y-auto">
                <div style="container-type: size;">
                    <div class="chat chat-end">
                        <div class="chat-header">
                            You
                            <time class="text-xs opacity-50">12:40</time>
                        </div>
                        <div class="chat-bubble">Hi Valo, I need help planning a new web application project.</div>
                    </div>
                    <div class="chat chat-start">
                        <div class="chat-header">
                            Valo
                            <time class="text-xs opacity-50">12:40</time>
                        </div>
                        <div class="chat-bubble">Hello! I'd be happy to help you plan your web application. What kind of application are you thinking of building?</div>
                    </div>
                    <div class="chat chat-end">
                        <div class="chat-header">
                            You
                            <time class="text-xs opacity-50">12:41</time>
                        </div>
                        <div class="chat-bubble">I want to create a task management system for teams.</div>
                    </div>
                    <div class="chat chat-start">
                        <div class="chat-header">
                            Valo
                            <time class="text-xs opacity-50">12:41</time>
                        </div>
                        <div class="chat-bubble">Great idea! Task management systems are very useful. Are you planning to use any specific technology stack?</div>
                    </div>
                    <div class="chat chat-end">
                        <div class="chat-header">
                            You
                            <time class="text-xs opacity-50">12:42</time>
                        </div>
                        <div class="chat-bubble">I'm thinking Laravel and Livewire for the backend, with Tailwind CSS.</div>
                    </div>
                    <div class="chat chat-start">
                        <div class="chat-header">
                            Valo
                            <time class="text-xs opacity-50">12:42</time>
                        </div>
                        <div class="chat-bubble">Excellent choice! That stack works very well together. What core features do you want to include?</div>
                    </div>
                    <div class="chat chat-end">
                        <div class="chat-header">
                            You
                            <time class="text-xs opacity-50">12:43</time>
                        </div>
                        <div class="chat-bubble">Task creation, assignments, due dates, comments, and file attachments.</div>
                    </div>
                    <div class="chat chat-start">
                        <div class="chat-header">
                            Valo
                            <time class="text-xs opacity-50">12:43</time>
                        </div>
                        <div class="chat-bubble">Those are solid core features. Have you considered adding real-time notifications for task updates?</div>
                    </div>
                    <div class="chat chat-end">
                        <div class="chat-header">
                            You
                            <time class="text-xs opacity-50">12:44</time>
                        </div>
                        <div class="chat-bubble">Yes! That would be great. How would I implement that with Livewire?</div>
                    </div>
                    <div class="chat chat-start">
                        <div class="chat-header">
                            Valo
                            <time class="text-xs opacity-50">12:44</time>
                        </div>
                        <div class="chat-bubble">You can use Laravel Echo with Pusher or Laravel Reverb for WebSocket connections. Livewire integrates seamlessly with both.</div>
                    </div>
                    <div class="chat chat-end">
                        <div class="chat-header">
                            You
                            <time class="text-xs opacity-50">12:45</time>
                        </div>
                        <div class="chat-bubble">What about the database structure? Any recommendations?</div>
                    </div>
                    <div class="chat chat-start">
                        <div class="chat-header">
                            Valo
                            <time class="text-xs opacity-50">12:45</time>
                        </div>
                        <div class="chat-bubble">I'd suggest tables for users, teams, projects, tasks, comments, and attachments. Use proper foreign keys and indexes for performance.</div>
                    </div>
                    <div class="chat chat-end">
                        <div class="chat-header">
                            You
                            <time class="text-xs opacity-50">12:46</time>
                        </div>
                        <div class="chat-bubble">Should I implement user roles and permissions from the start?</div>
                    </div>
                    <div class="chat chat-start">
                        <div class="chat-header">
                            Valo
                            <time class="text-xs opacity-50">12:46</time>
                        </div>
                        <div class="chat-bubble">Absolutely! Use Laravel's built-in authorization features or Spatie's Permission package. It's easier to add early than to retrofit later.</div>
                    </div>
                    <div class="chat chat-end">
                        <div class="chat-header">
                            You
                            <time class="text-xs opacity-50">12:47</time>
                        </div>
                        <div class="chat-bubble">What about testing? Should I write tests as I go?</div>
                    </div>
                    <div class="chat chat-start">
                        <div class="chat-header">
                            Valo
                            <time class="text-xs opacity-50">12:47</time>
                        </div>
                        <div class="chat-bubble">Yes! Write feature tests for your main workflows and unit tests for complex business logic. Laravel makes testing quite straightforward.</div>
                    </div>
                    <div class="chat chat-end">
                        <div class="chat-header">
                            You
                            <time class="text-xs opacity-50">12:48</time>
                        </div>
                        <div class="chat-bubble">How should I handle file uploads for attachments?</div>
                    </div>
                    <div class="chat chat-start">
                        <div class="chat-header">
                            Valo
                            <time class="text-xs opacity-50">12:48</time>
                        </div>
                        <div class="chat-bubble">Use Laravel's filesystem abstraction with S3 or local storage. Livewire has excellent file upload support with progress indicators built-in.</div>
                    </div>
                    <div class="chat chat-end">
                        <div class="chat-header">
                            You
                            <time class="text-xs opacity-50">12:49</time>
                        </div>
                        <div class="chat-bubble">This is really helpful! Any final tips before I start building?</div>
                    </div>
                    <div class="chat chat-start">
                        <div class="chat-header">
                            Valo
                            <time class="text-xs opacity-50">12:49</time>
                        </div>
                        <div class="chat-bubble">Start with an MVP featuring just core functionality. Use version control from day one, and don't forget to add proper error handling and logging. Good luck with your project!</div>
                    </div>
                    <div class="h-5"></div>
                </div>
            </div>

            <x-form no-separator>
                <x-textarea wire:model="message" placeholder="Ask anything" rows="3" class="resize-none" />

                <x-slot:actions>
                    <x-button label="Send" icon-right="fal.paper-plane-top" type="submit" class="btn-primary" spinner />
                </x-slot:actions>
            </x-form>
        </div>
    </x-slot:content>
</x-main>
