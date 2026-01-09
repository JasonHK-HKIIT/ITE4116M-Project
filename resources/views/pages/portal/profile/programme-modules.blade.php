<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::portal')] class extends Component {}; ?>

<div class="p-4 md:p-8">
    <x-card shadow>
        <h2 class="text-2xl font-bold text-primary mb-2">Programme & Modules</h2>
    </x-card>
</div>
