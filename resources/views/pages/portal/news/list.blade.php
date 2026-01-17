<?php

use App\Enums\NewsArticleStatus;
use App\Models\NewsArticle;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new #[Layout('layouts::portal')] class extends Component {
    use Toast, WithPagination;

    public int $perPage = 10;

    public bool $isDrawerOpened = false;

    #[Url(as: 'Search')]
    public ?string $keywords = null;

    public ?Carbon $publishedAfter = null;

    public ?Carbon $publishedBefore = null;

    #[Computed]
    public function articles(): LengthAwarePaginator
    {
        return NewsArticle::query()
            ->where('status', NewsArticleStatus::Published)
            ->when($this->keywords, function ($query, $keywords) {
                $query->whereHas('translations', fn ($q) => $q->whereFullText(['title', 'content'], $keywords));
            })
            ->when($this->publishedAfter, function ($query, $publishedAfter) {
                $query->where('published_on', '>=', $publishedAfter);
            })
            ->when($this->publishedBefore, function ($query, $publishedBefore) {
                $query->where('published_on', '<=', $publishedBefore);
            })
            ->orderByDesc('published_on')
            ->paginate($this->perPage);
    }

    public function with(): array
    {
        return [
            'articles' => $this->articles(),
        ];
    }

    public function clear()
    {
        $this->reset([
            'keywords',
            'publishedAfter',
            'publishedBefore'
        ]);
    }
}; ?>

@assets
    @vite([
        'resources/css/vendor/flatpickr.css',
        'resources/js/vendor/flatpickr.js'
    ])
@endassets

<div class="p-4 md:p-8 space-y-6 max-w-7xl mx-auto">
    <x-header :title="__('News & Announcement')" separator>
        <x-slot:middle class="!justify-end max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('Search...')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive />
        </x-slot:actions>
    </x-header>

    <x-drawer wire:model="isDrawerOpened" title="Filters" right separator with-close-button class="w-3/5 md:w-1/2 lg:w-1/3">
        <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('Search...')" />
        <x-datepicker label="Published After" wire:model.live="publishedAfter" clearable />
        <x-datepicker label="Published Before" wire:model.live="publishedBefore" clearable />

        <x-slot:actions>
            <x-button label="Reset" icon="fal.xmark" wire:click="clear" spinner />
            <x-button label="Done" icon="fal.check" class="btn-primary" @click="$wire.isDrawerOpened = false" />
        </x-slot:actions>
    </x-drawer>

    <x-card shadow>
        <div class="flex items-center justify-between mb-2">
            <div></div>
            <div>
                <select wire:model.live="perPage" class="select select-bordered select-sm w-24">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                </select>
            </div>
        </div>
        @if ($articles->count() === 0)
            <div class="text-center text-base-content/60 py-12">{{ __('No news found') }}</div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($articles as $article)
                    <x-card class="border border-base-200 h-full">
                        <div class="flex flex-col gap-3 h-full">
                            <img src="{{ $article->getFirstMediaUrl('cover') }}" alt="Cover" class="w-full h-36 object-cover rounded" />
                            <h3 class="text-lg font-semibold min-h-[3.5rem] line-clamp-3">{{ $article->title }}</h3>
                            <div class="flex items-center justify-between text-sm text-base-content/60 mt-auto">
                                <div class="flex items-center gap-1">
                                    {{ $article->published_on?->format('Y-m-d') }}
                                </div>
                                <a href="{{ route('portal.news.show', $article->id) }}" class="flex items-center gap-1 text-primary hover:underline">
                                    <span>View Details</span>
                                    <x-icon name="fal.eye" class="w-4 h-4" />
                                </a>
                            </div>
                        </div>
                    </x-card>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $articles->links() }}
            </div>
        @endif
    </x-card>
</div>
