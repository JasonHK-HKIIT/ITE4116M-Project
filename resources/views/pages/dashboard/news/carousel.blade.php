<?php

use App\Helpers\LocalesHelper;
use App\Models\CarouselSlide;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use Toast;
    use WithFileUploads;

    public bool $modal = false;

    public string $selectedTab = 'en';

    public CarouselSlide $slide;

    #[Validate('required')]
    public array $title = [];

    public array $description = [];

    public ?string $link_url = null;

    public bool $is_active = true;

    public ?TemporaryUploadedFile $image = null;

    public function mount(): void
    {
        $this->slide = CarouselSlide::make();
    }

    public function render()
    {
        return $this->view()->title(trans('news.carousel.title'));
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'preview', 'label' => trans('news.carousel.table.image'), 'sortable' => false],
            ['key' => 'title', 'label' => trans('news.carousel.table.title'), 'sortable' => false],
            ['key' => 'link_url', 'label' => trans('news.carousel.table.link_url'), 'sortable' => false],
            ['key' => 'is_active', 'label' => trans('news.carousel.table.status'), 'sortable' => false],
            ['key' => 'position', 'label' => trans('news.carousel.table.position'), 'sortable' => false],
        ];
    }

    public function carouselSlides(): Collection
    {
        return CarouselSlide::query()
            ->orderBy('position')
            ->orderBy('id')
            ->get();
    }

    public function createSlide(): void
    {
        $this->slide = CarouselSlide::make();
        $this->showModal();
    }

    public function editSlide(int $id): void
    {
        $this->slide = CarouselSlide::query()->findOrFail($id);
        $this->showModal();
    }

    public function deleteSlide(int $id): void
    {
        $slide = CarouselSlide::query()->findOrFail($id);
        $slide->delete();

        $this->normalizePositions();
        $this->success(trans('news.carousel.messages.deleted'));
    }

    public function moveUp(int $id): void
    {
        $slide = CarouselSlide::query()->findOrFail($id);

        $previous = CarouselSlide::query()
            ->where('position', '<', $slide->position)
            ->orderByDesc('position')
            ->first();

        if (!$previous)
        {
            return;
        }

        $oldPosition = $slide->position;
        $slide->position = $previous->position;
        $previous->position = $oldPosition;

        $slide->save();
        $previous->save();

        $this->normalizePositions();
        $this->success(trans('news.carousel.messages.moved'));
    }

    public function moveDown(int $id): void
    {
        $slide = CarouselSlide::query()->findOrFail($id);

        $next = CarouselSlide::query()
            ->where('position', '>', $slide->position)
            ->orderBy('position')
            ->first();

        if (!$next)
        {
            return;
        }

        $oldPosition = $slide->position;
        $slide->position = $next->position;
        $next->position = $oldPosition;

        $slide->save();
        $next->save();

        $this->normalizePositions();
        $this->success(trans('news.carousel.messages.moved'));
    }

    public function save(): void
    {
        $isNew = !$this->slide->exists;

        if ($isNew && !$this->image)
        {
            $this->addError('image', trans('news.carousel.messages.image_required'));
            return;
        }

        $fields = LocalesHelper::transformToModelFields($this->validate(), $this->slide->translatedAttributes);

        $this->slide->fill([
            'link_url' => $fields['link_url'],
            'is_active' => $fields['is_active'],
        ]);

        if ($isNew)
        {
            $maxPosition = (int) CarouselSlide::query()->max('position');
            $this->slide->position = $maxPosition + 1;
        }

        $this->slide->fill(collect($fields)->except(['link_url', 'is_active'])->toArray());
        $this->slide->save();

        if ($this->image)
        {
            $this->slide->addMedia($this->image->getRealPath())
                ->usingFileName($this->image->getClientOriginalName())
                ->toMediaCollection('image');
        }

        $this->modal = false;
        $this->success(trans('news.carousel.messages.saved'));
    }

    protected function rules(): array
    {
        return array_merge(
            LocalesHelper::buildRules('title', ['required', 'max:255']),
            LocalesHelper::buildRules('description', ['nullable']),
            [
                'link_url' => [
                    'nullable',
                    'max:255',
                    function ($attribute, $value, $fail)
                    {
                        if (!$value)
                        {
                            return;
                        }

                        if (str_starts_with($value, '/'))
                        {
                            return;
                        }

                        if (filter_var($value, FILTER_VALIDATE_URL))
                        {
                            return;
                        }

                        $fail('The link URL must be a valid URL or start with /.');
                    },
                ],
                'is_active' => ['boolean'],
                'image' => ['nullable', 'image', 'max:5120'],
            ]
        );
    }

    protected function validationAttributes(): array
    {
        return array_merge(
            LocalesHelper::buildValidationAttributes('title', trans('news.carousel.fields.title')),
            LocalesHelper::buildValidationAttributes('description', trans('news.carousel.fields.description'))
        );
    }

    protected function normalizePositions(): void
    {
        $slides = CarouselSlide::query()->orderBy('position')->orderBy('id')->get();

        foreach ($slides as $index => $slide)
        {
            $target = $index + 1;

            if ((int) $slide->position !== $target)
            {
                $slide->position = $target;
                $slide->save();
            }
        }
    }

    protected function showModal(): void
    {
        $this->resetErrorBag();

        $this->title = LocalesHelper::buildPropertyValue();
        $this->description = LocalesHelper::buildPropertyValue();
        $this->link_url = null;
        $this->is_active = true;
        $this->image = null;

        if ($this->slide->exists)
        {
            $this->fill(LocalesHelper::transformToProperties($this->slide->getTranslationsArray()));
            $this->link_url = $this->slide->link_url;
            $this->is_active = (bool) $this->slide->is_active;
        }

        $this->modal = true;
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'slides' => $this->carouselSlides(),
            'exists' => $this->slide->exists,
        ];
    }
}; ?>

<div>
    <x-header :title="__('news.carousel.title')" :subtitle="__('news.carousel.subtitle')" separator>
        <x-slot:actions>
            <x-button :label="__('news.carousel.create_slide')" icon="fal.plus" class="btn-primary" wire:click="createSlide" spinner="createSlide" responsive />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$slides">
            @scope('cell_preview', $slide)
                <img src="{{ $slide->getFirstMediaUrl('image') }}" alt="{{ $slide->title }}" class="h-12 w-20 rounded object-cover" />
            @endscope

            @scope('cell_title', $slide)
                {{ $slide->title }}
            @endscope

            @scope('cell_link_url', $slide)
                {{ $slide->link_url ?: '-' }}
            @endscope

            @scope('cell_is_active', $slide)
                <x-badge :value="$slide->is_active ? __('news.carousel.statuses.active') : __('news.carousel.statuses.inactive')" :class="$slide->is_active ? 'badge-success badge-soft' : 'badge-ghost'" />
            @endscope

            @scope('cell_position', $slide)
                {{ $slide->position }}
            @endscope

            @scope('actions', $slide)
                <div class="hidden lg:inline-flex flex-row w-8 lg:w-28">
                    <x-button icon="fal.arrow-up" :tooltip="__('actions.back')" wire:click="moveUp({{ $slide->id }})" class="btn-ghost btn-square btn-sm" spinner />
                    <x-button icon="fal.arrow-down" :tooltip="__('actions.done')" wire:click="moveDown({{ $slide->id }})" class="btn-ghost btn-square btn-sm" spinner />
                    <x-button icon="fal.pen-to-square" :tooltip="__('actions.edit')" wire:click="editSlide({{ $slide->id }})" class="btn-ghost btn-square btn-sm" spinner />
                    <x-button icon="fal.trash" :tooltip="__('actions.delete')" wire:click="deleteSlide({{ $slide->id }})" class="btn-ghost btn-square btn-sm" spinner />
                </div>

                <x-dropdown right>
                    <x-slot:trigger>
                        <x-button icon="fal.ellipsis-vertical" class="btn-ghost btn-square btn-sm lg:hidden" />
                    </x-slot:trigger>

                    <x-menu-item :title="__('actions.edit')" icon="fal.pen-to-square" wire:click="editSlide({{ $slide->id }})" />
                    <x-menu-item :title="__('actions.delete')" icon="fal.trash" wire:click.stop="deleteSlide({{ $slide->id }})" />
                    <x-menu-item :title="__('actions.back')" icon="fal.arrow-up" wire:click.stop="moveUp({{ $slide->id }})" />
                    <x-menu-item :title="__('actions.done')" icon="fal.arrow-down" wire:click.stop="moveDown({{ $slide->id }})" />
                </x-dropdown>
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="modal" :title="__($exists ? 'news.carousel.update_slide' : 'news.carousel.create_slide')" persistent>
        <x-form wire:submit="save" no-separator>
            <x-tabs wire:model="selectedTab" label-div-class="border-b-[length:var(--border)] border-b-base-content/10 flex flex-wrap overflow-x-auto">
                @foreach (LocalesHelper::locales() as $language)
                    <x-tab :name="$language" :label="__('languages.' . $language)" class="pb-0">
                        <x-input :label="__('news.carousel.fields.title')" wire:model="title.{{ $language }}" />
                        <x-textarea :label="__('news.carousel.fields.description')" wire:model="description.{{ $language }}" rows="4" />
                    </x-tab>
                @endforeach

                <div class="px-1 space-y-4">
                    <x-input :label="__('news.carousel.fields.link_url')" wire:model="link_url" placeholder="/news/article-slug or https://example.com" />
                    <x-toggle :label="__('news.carousel.fields.is_active')" wire:model="is_active" />
                    <x-file :label="__('news.carousel.fields.image')" wire:model="image" accept="image/*" />

                    @if ($slide->exists)
                        <div class="pt-2">
                            <img src="{{ $slide->getFirstMediaUrl('image') }}" alt="{{ $slide->title }}" class="h-24 rounded object-cover" />
                        </div>
                    @endif
                </div>
            </x-tabs>

            <x-slot:actions>
                <x-button :label="__('actions.cancel')" @click="$wire.modal = false" />
                <x-button :label="__($exists ? 'actions.save' : 'actions.create')" :icon="'fal.' . ($exists ? 'floppy-disk' : 'plus')" type="submit" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
