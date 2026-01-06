<?php

use App\Enums\NewsArticleStatus;
use App\Helpers\LocalesHelper;
use App\Models\NewsArticle;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\Rule;
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

    public NewsArticleStatus $status = NewsArticleStatus::Draft;

    #[Validate('required')]
    public ?Carbon $published_on = null;

    #[Validate('required')]
    public array $content = [];

    public array $statuses = [
            ['id' => NewsArticleStatus::Draft, 'name' => 'Draft'],
            ['id' => NewsArticleStatus::Published, 'name' => 'Published'],
        ];

    protected function rules(): array
    {
        return array_merge(
            [
                'status' => ['required', Rule::enum(NewsArticleStatus::class)],
            ],
            LocalesHelper::buildRules('title', ['required', 'max:255']),
            LocalesHelper::buildRules('content', ['required', 'max:16777215']),
        );
    }

    protected function validationAttributes()
    {
        return array_merge(
            LocalesHelper::buildValidationAttributes('title'),
            LocalesHelper::buildValidationAttributes('content'),
        );
    }

    public function mount(NewsArticle $article)
    {
        $this->exists = $article->exists;
        $this->article = $article;

        if ($this->exists)
        {
            $this->fill($article->only(['slug', 'status', 'published_on']));
            $this->fill(LocalesHelper::transformToProperties($article->getTranslationsArray()));
        }
        else
        {
            $this->content = LocalesHelper::buildPropertyValue();
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

    // public function with(): array
    // {
    //     return [
    //         'statuses' => $this->statuses(),
    //     ];
    // }
}; ?>

@assets
    @vite([
        'resources/css/vendor/flatpickr.css',
        'resources/js/vendor/flatpickr.js',
        'resources/js/vendor/tinymce.js'
    ])
@endassets

<div>
    <x-header :title="__('News & Announcement')" :subtitle="($exists ? 'Update' : 'Create') . ' Article'" separator>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <x-tabs wire:model="selectedLanguage">
                @foreach (LocalesHelper::locales() as $language)
                    <x-tab wire:key="tab.{{ $language }}.1" :name="$language" :label="__('languages.' . $language)" class="pb-0">
                        <x-input label="Title" wire:model="title.{{ $language }}" />
                    </x-tab>
                @endforeach
                    <div class="px-1">
                        <x-input label="Slug" wire:model="slug" prefix="/news/" class="" />
                        <x-group label="Status" wire:model="status" :options="$statuses" />
                        <x-datepicker label="Published on" wire:model="published_on" />
                    </div>
                @foreach (LocalesHelper::locales() as $language)
                    <x-tab wire:key="tab.{{ $language }}.2" :name="$language" :label="__('languages.' . $language)" class="pt-0">
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
