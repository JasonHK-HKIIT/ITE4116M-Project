<?php

use App\Enums\InformationCentreStatus;
use App\Models\InformationCentre;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new
#[Layout('layouts::portal')]
class extends Component
{
    use WithPagination;

    public int $perPage = 10;

    public bool $isDrawerOpened = false;

    public string $keywords = '';

    public array $expanded = []; //for expandable rows button

    public function headers(): array
    {
        return [
            ['key' => 'title', 'label' => 'Document Group', 'class' => 'w-auto min-w-64'],
            ['key' => 'latest_date', 'label' => 'Latest Release', 'class' => 'w-32', 'format' => ['date', 'Y-m-d']],
        ];
    }

    public function documents()
    {
        $query = InformationCentre::where('status', InformationCentreStatus::Published)
            ->when($this->keywords, function ($query, $keywords) {
                return $query->whereFullText(['title', 'subtitle'], $keywords);
            })
            ->orderBy('title')
            ->orderBy('published_on', 'desc')
            ->get()
            ->groupBy('title')
            ->map(function ($items, $title) {
                return (object)[
                    'id' => $items->first()->id,
                    'title' => $title,
                    'count' => $items->count(),
                    'documents' => $items,
                    'latest_date' => $items->max('published_on'),
                ];
            })
            ->sortByDesc('latest_date')
            ->values();


        $page = $this->getPage();
        $perPage = $this->perPage;
        $total = $query->count();
        $items = $query->slice(($page - 1) * $perPage, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => route('portal.information-centre'), 'query' => request()->query()]
        );
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'documents' => $this->documents(),
        ];
    }
}; ?>

<div>
    <x-header title="Information Centre" separator>
        <x-slot:middle class="!justify-end max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" placeholder="Search..." />
        </x-slot:middle>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$documents" with-pagination per-page="perPage" :per-page-values="[5, 10, 15]" expandable :expandable-key="'id'" wire:model="expanded">
            @scope('cell_title', $group)
                <div class="flex items-center gap-2">
                    <x-icon name="o-book-open" class="w-5 h-5 text-primary" />
                    <span>{{ $group->title }}</span>
                </div>
            @endscope

            @scope('expansion', $group)
                <div class="bg-base-200 p-4">
                    <div class="space-y-2">
                        @foreach($group->documents as $document)
                            <div class="flex justify-between items-center bg-white p-3 rounded">
                                <div>
                                    <p class="font-semibold">{{ $document->subtitle }}</p>
                                    <p class="text-sm text-gray-600">Released: {{ $document->published_on->format('Y-m-d') }}</p>
                                </div>
                                <a href="{{ route('portal.information-centre.download', $document->id) }}" class="btn btn-ghost btn-sm" target="_blank">
                                    <x-icon name="o-arrow-down-tray" class="w-5 h-5" />
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endscope
        </x-table>
    </x-card>
</div>
