<?php
use App\Models\NewsArticle;
use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout('layouts::portal')]
class extends Component
{
    public NewsArticle $article;
}; ?>

<div class="p-4 md:p-8">
    <x-card :title="$article->title" :subtitle="$article->published_on?->format('Y-m-d')" shadow separator>
        <div class="prose max-w-none mb-4">{!! $article->content !!}</div>
        <x-slot:figure>
            <img src="{{ $article->getFirstMediaUrl('cover') }}" alt="{{ __('news.fields.image') }}" class="w-full h-auto max-h-[600px] object-contain rounded" />
        </x-slot:figure>
        <x-slot:actions separator>
            <a href="{{ route('portal.news.list') }}" class="btn btn-primary">{{ __('news.back_to_list') }}</a>
        </x-slot:actions>
    </x-card>
</div>
