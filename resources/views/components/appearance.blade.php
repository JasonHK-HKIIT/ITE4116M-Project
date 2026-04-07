<?php

use Livewire\Attributes\Modelable;
use Livewire\Attributes\Session;
use Livewire\Component;

new class extends Component
{
    #[Modelable]
    public bool $opened = false;

    #[Session(key: 'language')]
    public string $language = 'en';

    #[Session(key: 'theme')]
    public string $theme = 'auto';

    public function with(): array
    {
        return [
            'languages' => $this->languages(),
            'themes' => $this->themes(),
        ];
    }

    public function languages(): array
    {
        return [
            ['id' => 'en', 'name' => 'English'],
            ['id' => 'zh-HK', 'name' => '中文（繁體）'],
            ['id' => 'zh-CN', 'name' => '中文（简体）'],
        ];
    }

    public function themes(): array
    {
        return [
            ['id' => 'auto', 'name' => trans('appearance.theme_values.auto')],
            ['id' => 'light', 'name' => trans('appearance.theme_values.light')],
            ['id' => 'dark', 'name' => trans('appearance.theme_values.dark')],
        ];
    }

    public function save(): void
    {
        $this->js('location.reload()');
    }
}; ?>

<div>
    @teleport('body')
        <x-modal wire:model="opened" :title="__('appearance.title')" :subtitle="__('appearance.subtitle')">
            <x-form wire:submit="save" no-separator>
                <x-select :label="__('appearance.language')" wire:model="language" :options="$languages" />
                <x-select :label="__('appearance.theme')" wire:model="theme" :options="$themes" />

                <x-slot:actions>
                    <x-button :label="__('actions.save')" :icon="'fal.floppy-disk'" type="submit" class="btn-primary" spinner="save" />
                </x-slot:actions>
            </x-form>
        </x-modal>
    @endteleport
</div>
