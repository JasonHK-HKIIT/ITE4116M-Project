<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts::portal')]
class extends Component
{
    use WithPagination;

    public array $expanded = [];

    public function mount()
    {
        $this->expanded = $this->studentProgrammes()->pluck('id')->toArray();
    }

    public function headers(): array
    {
        return [
            ['key' => 'programme', 'label' => 'Programme', 'class' => 'w-auto min-w-64'],
        ];
    }

    public function studentProgrammes()
    {
        $user = Auth::user();
        if ($user->role === 'admin' || !$user->student) {
            return collect();
        }
        return $user->student->programmes();
    }


    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'programmes' => $this->studentProgrammes(),
        ];
    }
};
?>

<div>
    <x-header :title="__('Programme & Modules')" separator />

    <x-card shadow>
        @if(Auth::user()->role === 'admin' || !Auth::user()->student)
            <div class="p-4">
                <div class="font-semibold mb-2">No Programme Code - No Programme</div>
                <div class="bg-base-200 p-4">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>Module Code</th>
                                <th>Module Title</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center text-gray-500">No modules.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <x-table :headers="$headers" :rows="$programmes" expandable :expandable-key="'id'" wire:model="expanded">
                @scope('cell_programme', $programme)
                    <span>{{ $programme->programme_code }} - {{ $programme->name }}</span>
                @endscope
                @scope('expansion', $programme)
                    <div class="bg-base-200 p-4">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Module Code</th>
                                    <th>Module Title</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($programme->modules->isEmpty())
                                    <tr>
                                        <td colspan="3" class="text-center text-gray-500">No modules.</td>
                                    </tr>
                                @else
                                    @foreach($programme->modules as $module)
                                        <tr>
                                            <td>{{ $module->module_code }}</td>
                                            <td>{{ $module->name }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endscope
            </x-table>
        @endif
    </x-card>
</div>
