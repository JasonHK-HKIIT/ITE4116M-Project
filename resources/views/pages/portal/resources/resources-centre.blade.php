<?php

use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new
#[Layout('layouts::portal')]
class extends Component
{
    use WithPagination;

    public int $perPage = 10;

    public bool $isDrawerOpened = false;

    #[Url(as: 'Search')]
    public string $keywords = '';

    public ?Carbon $publishedAfter = null;

    public ?Carbon $publishedBefore = null;

    //for expandable rows button
    public array $expanded = []; 

    public function headers(): array
    {
        return [
            ['key' => 'title', 'label' => trans('resources.table.document_group'), 'class' => 'w-auto min-w-64'],
            ['key' => 'latest_date', 'label' => trans('resources.table.latest_release'), 'class' => 'w-32', 'format' => ['date', 'Y-m-d']],
        ];
    }

    public function documents()
    {
        $locale = app()->getLocale();
        $query = \App\Models\Resource::query()
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }, 'translations.media']);

        if ($this->keywords) {
            $query->whereHas('translations', function ($q) use ($locale) {
                $q->where('locale', $locale)
                  ->whereFullText(['title'], $this->keywords);
            });
        }

        if ($this->publishedAfter) {
            $query->where('updated_at', '>=', $this->publishedAfter);
        }
        if ($this->publishedBefore) {
            $query->where('updated_at', '<=', $this->publishedBefore);
        }

        $resources = $query->orderByDesc('updated_at')->paginate($this->perPage);

        $items = $resources->getCollection()->flatMap(function ($resource) {
            return $resource->translations->map(function ($translation) use ($resource) {
                return (object) [
                    'id' => $translation->id,
                    'title' => $translation->title,
                    'locale' => $translation->locale,
                    'media' => $translation->getMedia('resources'),
                    'resource_id' => $resource->id,
                    'latest_date' => $resource->updated_at,
                ];
            });
        })->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $resources->total(),
            $resources->perPage(),
            $resources->currentPage(),
            ['path' => route('portal.resources-centre'), 'query' => request()->query()]
        );
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'documents' => $this->documents(),
        ];
    }

    public function clear(): void
    {
        $this->reset([
            'keywords',
            'publishedAfter',
            'publishedBefore',
        ]);
    }
}; ?>

@assets
    @vite([
        'resources/css/vendor/flatpickr.css',
        'resources/js/vendor/flatpickr.js'
    ])
@endassets

<div>

    <x-header :title="__('resources.title')" separator>
        <x-slot:middle class="!justify-end max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('actions.search')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('actions.filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive />
        </x-slot:actions>
    </x-header>

    <x-drawer wire:model="isDrawerOpened" :title="__('actions.filters')" right separator with-close-button class="w-3/5 md:w-1/2 lg:w-1/3">
        <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" :placeholder="__('actions.search')" />
        <x-datepicker :label="__('resources.filters.latest_after')" wire:model.live="publishedAfter" clearable />
        <x-datepicker :label="__('resources.filters.latest_before')" wire:model.live="publishedBefore" clearable />

        <x-slot:actions>
            <x-button :label="__('actions.reset')" icon="fal.xmark" wire:click="clear" spinner />
            <x-button :label="__('actions.done')" icon="fal.check" class="btn-primary" @click="$wire.isDrawerOpened = false" />
        </x-slot:actions>
    </x-drawer>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$documents" expandable :expandable-key="'id'" wire:model="expanded">
            @scope('cell_title', $row)
                <div class="flex items-center gap-2">
                    <x-icon name="o-book-open" class="w-5 h-5 text-primary" />
                    <span>{{ $row->title }}</span>
                </div>
            @endscope

            @scope('expansion', $row)
                <div class="bg-base-200 p-4">
                    <div class="space-y-2">
                        @if($row->media->isEmpty())
                            <div class="text-gray-500">{{ __('resources.empty.no_files_uploaded') }}</div>
                        @else
                            @foreach($row->media as $media)
                                <div class="flex justify-between items-center bg-base-100 p-3 rounded">
                                    <div>
                                        <p class="font-semibold">{{ $media->file_name }}</p>
                                        <p class="text-sm text-gray-600">Size: {{ number_format($media->size / 1024, 2) }} KB</p>
                                    </div>
                                    <a href="{{ $media->getFullUrl() }}" class="btn btn-ghost btn-sm" target="_blank">
                                        <x-icon name="o-arrow-down-tray" class="w-5 h-5" />
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endscope
        </x-table>
        <x-pagination :rows="$documents" wire:model.live="perPage" :per-page-values="[5, 10, 15]" />
    </x-card>
</div>
