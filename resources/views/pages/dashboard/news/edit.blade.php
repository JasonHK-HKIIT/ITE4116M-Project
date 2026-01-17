<?php

use App\Enums\NewsArticleStatus;
use App\Helpers\LocalesHelper;
use App\Models\NewsArticle;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use Toast, WithFileUploads;

    public string $selectedLanguage = 'en';

    public bool $exists = false;

    public NewsArticle $article;

    // for add image
    public $cover;

    #[Validate('required')]
    public array $title = [];

    public string $slug = '';

    public NewsArticleStatus $status = NewsArticleStatus::Draft;

    public ?Carbon $published_on = null;

    #[Validate('required')]
    public array $content = [];

    #[Computed]
    public function statuses(): array
    {
        return collect(NewsArticleStatus::cases())
            ->map(fn($case, $key) => ['id' => $case, 'name' => $case->name])
            ->toArray();
    }

    protected function rules(): array
    {
        return array_merge(
            [
                'slug' => ['required', 'alpha_dash:ascii', 'max:127', Rule::unique('news_articles', 'slug')->ignore($this->article)],
                'status' => ['required', Rule::enum(NewsArticleStatus::class)],
                'published_on' => ['nullable', Rule::requiredIf(fn() => ($this->status == NewsArticleStatus::Published)), 'date']
            ],
            LocalesHelper::buildRules('title', ['required', 'max:255']),
            LocalesHelper::buildRules('content', ['required', 'max:16777215']),
        );
    }

    protected function validationAttributes(): array
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
        if (empty($this->slug)) { $this->slug = Str::slug($this->title['en'] ?? ''); }
        if (empty($this->published_on) && ($this->status == NewsArticleStatus::Published)) { $this->published_on = Carbon::today(); }

        $fields = LocalesHelper::transformToModelFields($this->validate(), $this->article->translatedAttributes);
        $this->article->fill($fields);
        $this->article->save();

        // Add image in storage
        if ($this->cover) {
            $this->article->clearMediaCollection('cover');
            $this->article->addMedia($this->cover->getRealPath())
                ->usingFileName($this->cover->getClientOriginalName())
                ->toMediaCollection('cover');
        }

        if ($this->exists)
        {
            $this->success('News article was updated.');
        }
        else
        {
            $this->success(
                "News article was created.",
                redirectTo: route('dashboard.news.edit', ['article' => $this->article]));
        }
    }

    public function with(): array
    {
        return [
            'statuses' => $this->statuses(),
        ];
    }
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
            <x-tabs wire:model="selectedLanguage" label-div-class="border-b-[length:var(--border)] border-b-base-content/10 flex flex-wrap overflow-x-auto">
                @foreach (LocalesHelper::locales() as $language)
                    <x-tab :name="$language" :label="__('languages.' . $language)" class="pb-0">
                        <x-input label="Title" wire:model="title.{{ $language }}" />
                    </x-tab>
                @endforeach
                    <div class="px-1">
                        <x-input label="Slug" wire:model="slug" prefix="/news/" popover="Unique identifier of the article" />
                        <x-group label="Status" wire:model="status" :options="$statuses" />
                        <x-datepicker label="Published on" wire:model="published_on" clearable />

                        <!--if selected image -> open, else if -> get the current image or default image-->
                        @if ($cover && Str::startsWith($cover->getMimeType(), 'image/'))
                            <br />
                            <img src="{{ $cover->temporaryUrl() }}" alt="Preview" class="max-h-48 rounded border" />
                        @elseif ($article->getFirstMediaUrl('cover'))
                            <br />
                            <img src="{{ $article->getFirstMediaUrl('cover') }}" alt="Current Image" class="max-h-48 rounded border" />
                        @endif
                        <x-input type="file" label="Image" wire:model="cover" accept="image/jpeg,image/png,image/jpg,image/bmp" />
                    </div>
                @foreach (LocalesHelper::locales() as $language)
                    <x-tab :name="$language" :label="__('languages.' . $language)" class="pt-0">
                        <x-editor label="Content" wire:model="content.{{ $language }}" gplLicense />
                    </x-tab>
                @endforeach
            </x-tabs>

            <x-slot:actions>
                <x-button label="Cancel" :link="route('dashboard.news.list')" />
                <x-button :label="($exists ? 'Save' : 'Create')" :icon="'fal.' . ($exists ? 'floppy-disk' : 'plus')" type="submit" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
