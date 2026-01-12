<?php
use App\Models\NewsArticle;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::portal')] class extends Component {
    public NewsArticle $article;

    public function mount($id)
    {
        $this->article = NewsArticle::findOrFail($id);
    }
}; ?>

<div class="p-4 md:p-8">
    <x-card :title="$article->title" :subtitle="$article->published_on?->format('Y-m-d')" shadow separator>
        <div class="prose max-w-none mb-4">{!! nl2br(e($article->content)) !!}</div>
        <x-slot:figure>
            <img src="{{ $article->getFirstMediaUrl('cover') }}" alt="Cover" class="w-full h-56 object-cover rounded" />
        </x-slot:figure>
        <x-slot:actions separator>
            <a href="{{ route('portal.news.list') }}" class="btn btn-primary">{{ __('Back to News List') }}</a>
        </x-slot:actions>
    </x-card>
</div>
