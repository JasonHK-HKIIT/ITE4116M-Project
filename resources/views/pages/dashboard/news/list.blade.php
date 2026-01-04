<?php

use App\Models\NewsArticle;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use WithPagination, WithoutUrlPagination;
    
    public array $sortBy = ['column' => 'published_on', 'direction' => 'desc'];

    public int $perPage = 10;

    public bool $isDrawerOpened = false;

    public string $keyword = '';

    public ?string $status = null;

    public function headers(): array
    {
        return [
            ['key' => 'title', 'label' => 'Title', 'class' => 'w-auto'],
            ['key' => 'language', 'label' => 'Language', 'class' => 'w-fit'],
            ['key' => 'is_published', 'label' => 'Status', 'class' => 'w-fit', 'format' => (fn($article, $is_published) => ($is_published ? 'Published' : 'Draft'))],
            ['key' => 'published_on', 'label' => 'Published on', 'class' => 'w-fit'],
        ];
    }

    public function articles()
    {
        return NewsArticle::query()
            ->when($this->keyword, function ($query, $keyword)
            {
                $query
                    ->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('content', 'like', '%' . $keyword . '%');
            })
            ->when($this->status, function ($query, $status)
            {
                $query->where('is_published', ($status == 'published'));
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    public function statuses(): array
    {
        return [
            ['id' => 'published', 'name' => 'Published'],
            ['id' => 'draft', 'name' => 'Draft'],
        ];
    }

    public function deleteArticle($id): void
    {

    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'articles' => $this->articles(),
            'statuses' => $this->statuses(),
        ];
    }
};
?>

<div>
    <x-header :title="__('News & Announcement')" separator>
        <x-slot:middle class="!justify-end max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keyword" type="search" :placeholder="__('Search...')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive />
            <x-button icon="fal.plus" class="btn-primary" :link="route('dashboard.news.create')" responsive />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$articles" :sort-by="$sortBy" with-pagination per-page="perPage" :per-page-values="[5, 10, 25]">
            @scope('actions', $article)
                <div class="flex flex-row w-8 lg:w-17">
                    <x-button icon="fal.pen-to-square" :tooltip="__('Edit')" :link="route('dashboard.news.edit', ['article' => $article])" class="btn-ghost btn-square btn-sm hidden lg:inline-flex" />
                    <x-button icon="fal.trash" :tooltip="__('Delete')" wire:click="deleteArticle({{ $article->id }})" spinner class="btn-ghost btn-square btn-sm hidden lg:inline-flex" />

                    <x-dropdown right>
                        <x-slot:trigger>
                            <x-button icon="fal.ellipsis-vertical" class="btn-ghost btn-square btn-sm lg:hidden" />
                        </x-slot:trigger>

                        <x-menu-item title="Edit" icon="fal.pen-to-square" :link="route('dashboard.news.edit', ['article' => $article])" />
                        <x-menu-item title="Delete" icon="fal.trash" wire:click.stop="deleteArticle({{ $article->id }})" spinner />
                    </x-dropdown>
                </div>
            @endscope
        </x-table>
    </x-card>

    <x-drawer wire:model="isDrawerOpened" title="Filters" right separator with-close-button class="w-3/5 md:w-1/2 lg:w-1/3">
        <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keyword" :placeholder="__('Search...')" />
        <x-select label="Status" wire:model.live.debounce="status" :options="$statuses" placeholder="Any" />

        <x-slot:actions>
            <x-button label="Reset" icon="fal.xmark" wire:click="clear" spinner />
            <x-button label="Done" icon="fal.check" class="btn-primary" @click="$wire.isDrawerOpened = false" />
        </x-slot:actions>
    </x-drawer>
</div>
