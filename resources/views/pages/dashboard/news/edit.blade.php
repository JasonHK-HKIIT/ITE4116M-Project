<?php

use App\Helpers\LocalesHelper;
use App\Models\NewsArticle;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    public string $selectedLanguage = 'en';

    public bool $exists = false;

    public NewsArticle $article;

    #[Validate('required')]
    public array $title = [];

    #[Validate(['required', 'alpha_dash:ascii', 'max:127'])]
    public string $slug = '';

    #[Validate('required')]
    public array $content = [];

    protected function rules(): array
    {
        return array_merge(
            LocalesHelper::getRules('title', ['required', 'max:255']),
            LocalesHelper::getRules('content', ['required', 'max:16777215']),
        );
    }

    protected function validationAttributes()
    {
        return array_merge(
            LocalesHelper::getValidationAttributes('title'),
            LocalesHelper::getValidationAttributes('content'),
        );
    }

    public function mount(NewsArticle $article)
    {
        $this->exists = $article->exists;
        $this->article = $article;

        if ($this->exists)
        {
            $this->fill($article->only(["slug"]));
            $this->fill(LocalesHelper::transformToProperties($article->getTranslationsArray()));
        }
    }

    public function render()
    {
        return $this->view()->title(($this->exists ? 'Update' : 'Create') . ' Article');
    }

    public function save(): void
    {
        $fields = LocalesHelper::transformToModelFields($this->validate(), $this->article->translatedAttributes);
        $this->article->fill($fields);
        $this->article->save();
    }
}; ?>

@push('scripts')
    @vite(['resources/js/vendor/tinymce.js'])
@endpush

<div>
    <x-header :title="__('News & Announcement')" :subtitle="($exists ? 'Update' : 'Create') . ' Article'" separator>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <x-tabs wire:model="selectedLanguage">
                @foreach (LocalesHelper::locales() as $language)
                    <x-tab wire:key="{{ $language }}" :name="$language" :label="__('languages.' . $language)">
                        <x-input label="Title" wire:model="title.{{ $language }}" />
                        <x-input label="Slug" wire:model="slug" prefix="/news/" />
                        <x-editor label="Content" wire:model="content.{{ $language }}" gplLicense />
                    </x-tab>
                @endforeach
            </x-tabs>

            <x-slot:actions>
                <x-button label="Cancel" />
                <x-button :label="($exists ? 'Save' : 'Create')" :icon="'fal.' . ($exists ? 'floppy-disk' : 'plus')" type="submit" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
