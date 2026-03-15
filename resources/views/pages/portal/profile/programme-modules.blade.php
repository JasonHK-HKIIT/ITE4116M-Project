<?php

use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::portal')] 
class extends Component {
    public ?Student $student = null;
    public $programmes = [];
    public $programmeModules = [];

    public function mount(): void
    {
        $this->student = Auth::user()->student;
        $locale = app()->getLocale();

        $studentClasses = $this->student?->classes()
            ->with(['programme' => function ($query) use ($locale) {
                $query->with(['programmeTranslation' => function ($q) use ($locale) {
                    $q->where('locale', $locale);
                }]);
            }])
            ->get() ?? collect();

        $this->programmes = $studentClasses->pluck('programme')->unique('id');

        $this->programmeModules = $this->programmes->mapWithKeys(function ($programme) use ($locale) {
            $modules = $programme->modules()
                ->with(['moduleTranslation' => function ($q) use ($locale) {
                    $q->where('locale', $locale);
                }])
                ->get();
            return [$programme->id => $modules];
        });
    }

    public function with(): array
    {
        return [
            'programmes' => $this->programmes,
            'programmeModules' => $this->programmeModules,
        ];
    }

}; ?>


<div class="p-4 md:p-8 space-y-6 max-w-7xl mx-auto">
    <x-header :title="__('Programme & Modules')" separator />
    
    @if($programmes->isEmpty())
        <x-card shadow>
            <div class="text-center py-12">
                <p class="text-base-content/60">{{ __('No programmes assigned yet.') }}</p>
            </div>
        </x-card>
    @else
        @foreach ($programmes as $programme)
            @php
                $translation = $programme->programmeTranslation->first();
                $programmeName = $translation?->name ?? $programme->programme_code;
                $modules = $programmeModules[$programme->id] ?? collect();
            @endphp
            
            <x-card shadow class="overflow-hidden">
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-base-200 pb-4">
                        <div>
                            <h3 class="font-bold ">{{ $programmeName }}</h3>
                            <p class="text-sm text-base-content/60">{{ __('Programme Code') }}: {{ $programme->programme_code }}</p>
                        </div>
                    </div>
                    
                    @if($modules->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-base-content/60">{{ __('No modules assigned for this programme.') }}</p>
                        </div>
                    @else
                        <div class="space-y-2">
                            @foreach ($modules as $module)
                                @php
                                    $moduleTranslation = $module->moduleTranslation->first();
                                    $moduleName = $moduleTranslation?->name ?? $module->module_code;
                                @endphp
                                
                                <div class="flex items-start gap-3 p-3 rounded-lg bg-base-100 hover:bg-base-200 transition-colors">
                                    <div class="flex-1">
                                        <p class="font-semibold text-base-content">{{ $moduleName }}</p>
                                        <p class="text-sm text-base-content/60">{{ __('Module Code') }}: {{ $module->module_code }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </x-card>
        @endforeach
    @endif
</div>
