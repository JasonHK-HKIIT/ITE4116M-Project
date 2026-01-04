<?php

use App\Models\NewsArticle;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    public NewsArticle $article;

    public ?int $id;

    #[Rule(['required'])]
    public string $title = '';

    #[Rule(['required'])]
    public string $slug = '';

    public function mount(?NewsArticle $article)
    {
        $this->article = $article;

        $this->id = $article->id;
        if ($this->id)
        {  
            $this->title = $article->title;
            $this->slug = $article->slug;
        }
    }

    public function render()
    {
        return $this->view()->title(($this->id ? 'Update' : 'Create') . ' Article');
    }

    public function save(): void
    {
        $fields = $this->validate();
        $this->article->fill($fields);
        $this->article->save();
    }
};
?>

<div>
    <x-header :title="__('News & Announcement')" :subtitle="($id ? 'Update' : 'Create') . ' Article'" separator>
        <x-slot:middle class="!justify-end max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="searchQuery" type="search" :placeholder="__('Search...')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive />
            <x-button icon="fal.plus" class="btn-primary" :link="route('dashboard.news.create')" responsive />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <x-input label="Title" wire:model="title" />
            <x-input label="Slug" wire:model="slug" prefix="/news/" />

            <x-slot:actions>
                <x-button label="Cancel" />
                <x-button :label="($id ? 'Save' : 'Create')" :icon="'fal.' . ($id ? 'floppy-disk' : 'plus')" type="submit" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
