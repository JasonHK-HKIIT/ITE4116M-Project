<?php

use App\Enums\NewsArticleStatus;
use App\Models\NewsArticle;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use Toast, WithPagination;
    
    public array $sortBy = ['column' => 'published_on', 'direction' => 'desc'];

    public int $perPage = 10;

    public bool $isDrawerOpened = false;

    public string $keywords = '';

    public ?string $status = null;

    public ?Carbon $publishedAfter = null;

    public ?Carbon $publishedBefore = null;

    public function headers(): array
    {
        return [
            ['key' => 'title', 'label' => 'Title', 'class' => 'w-auto min-w-64'],
            ['key' => 'status', 'label' => 'Status', 'class' => 'w-fit', 'sortable' => false, 'format' => (fn($article, $status) => __($status->value))],
            ['key' => 'published_on', 'label' => 'Published on', 'class' => 'w-fit', 'format' => ['date', 'Y-m-d']],
        ];
    }

    public function articles(): LengthAwarePaginator
    {
        $sortTranslation = in_array($this->sortBy['column'], ['title']);

        return NewsArticle::query()
            ->when($this->keywords, function ($query, $keywords)
            {
                $query->whereHas('translations', fn ($query) => $query->whereFullText(['title', 'content'], $keywords));
            })
            ->when($this->status, function ($query, $status)
            {
                $query->where('status', $status);
            })
            ->when($this->publishedAfter, function ($query, $publishedAfter)
            {
                $query->where('published_on', '>=', $publishedAfter);
            })
            ->when($this->publishedBefore, function ($query, $publishedBefore)
            {
                $query->where('published_on', '<=', $publishedBefore);
            })
            ->when((($this->sortBy['column'] == 'published_on') ? $this->sortBy : false), function ($query, $sortBy)
            {
                $query->orderBy('status', ($sortBy['direction'] == 'asc') ? 'desc' : 'asc');
            })
            ->when((($this->sortBy['column'] == 'published_on') ? $this->sortBy : false), function ($query, $sortBy)
            {
                $query->orderBy('status', ($sortBy['direction'] == 'asc') ? 'desc' : 'asc');
            })
            ->when(($sortTranslation ? $this->sortBy : false), function ($query, $sortBy)
            {
                $query->orderByTranslation($this->sortBy['column'], $this->sortBy['direction']);
            })
            ->when((!$sortTranslation ? $this->sortBy : false), function ($query, $sortBy)
            {
                $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
            })
            ->paginate($this->perPage);
    }

    public function statuses(): array
    {
        return collect(NewsArticleStatus::cases())
            ->map(fn($case, $key) => ['id' => $case, 'name' => $case->name])
            ->toArray();
    }

    public function deleteArticle(int $id): void
    {
        if (NewsArticle::destroy($id) > 0)
        {
            $this->success('News article was deleted.');
        }
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'articles' => $this->articles(),
            'statuses' => $this->statuses(),
        ];
    }
}; ?>

@assets
    @vite([
        'resources/css/vendor/flatpickr.css',
        'resources/js/vendor/flatpickr.js'
    ])
@endassets

<div>
    <x-header :title="__('News & Announcement')" separator>
        <x-slot:middle class="!justify-end max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('Search...')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive />
            <x-button icon="fal.plus" class="btn-primary" :link="route('dashboard.news.create')" responsive />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$articles" :sort-by="$sortBy" with-pagination per-page="perPage" :per-page-values="[5, 10, 25]">
            @scope('actions', $article)
                <div class="hidden lg:inline-flex flex-row w-8 lg:w-17">
                    <x-button icon="fal.pen-to-square" :tooltip="__('Edit')" :link="route('dashboard.news.edit', ['article' => $article])" class="btn-ghost btn-square btn-sm" />
                    <x-button icon="fal.trash" :tooltip="__('Delete')" wire:click="deleteArticle({{ $article->id }})" spinner class="btn-ghost btn-square btn-sm" />
                </div>

                <x-dropdown right>
                    <x-slot:trigger>
                        <x-button icon="fal.ellipsis-vertical" class="btn-ghost btn-square btn-sm lg:hidden" />
                    </x-slot:trigger>

                    <x-menu-item title="Edit" icon="fal.pen-to-square" :link="route('dashboard.news.edit', ['article' => $article])" />
                    <x-menu-item title="Delete" icon="fal.trash" wire:click.stop="deleteArticle({{ $article->id }})" spinner />
                </x-dropdown>
            @endscope
        </x-table>
    </x-card>

    <x-drawer wire:model="isDrawerOpened" title="Filters" right separator with-close-button class="w-3/5 md:w-1/2 lg:w-1/3">
        <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" :placeholder="__('Search...')" />
        <x-select label="Status" wire:model.live.debounce="status" :options="$statuses" placeholder="Any" />
        <x-datepicker label="Published After" wire:model.live="publishedAfter" clearable />
        <x-datepicker label="Published Before" wire:model.live="publishedBefore" clearable />

        <x-slot:actions>
            <x-button label="Reset" icon="fal.xmark" wire:click="clear" spinner />
            <x-button label="Done" icon="fal.check" class="btn-primary" @click="$wire.isDrawerOpened = false" />
        </x-slot:actions>
    </x-drawer>
</div>
