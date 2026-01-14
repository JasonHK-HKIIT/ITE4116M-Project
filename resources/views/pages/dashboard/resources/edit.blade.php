<?php

use App\Helpers\LocalesHelper;
use App\Models\Resource;
use App\Models\ResourceTranslation;
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

    public Resource $resource;

    // multiple language file uploads(for pending files before save)
    public array $pendingFiles = [];
    // multiple language file delete(for mark delayed deletion media files before save)
    public array $pendingDeleteMedia = [];

    #[Validate('required')]
    public array $title = [];


    #[Computed]
    public function locales(): array
    {
        return LocalesHelper::locales();
    }

    protected function rules(): array
    {
        return LocalesHelper::buildRules('title', ['required', 'max:255']);
    }

    protected function validationAttributes()
    {
        return LocalesHelper::buildValidationAttributes('title');
    }

    public function mount(Resource $resource)
    {
        $this->exists = $resource->exists;
        $this->resource = $resource;
        foreach (LocalesHelper::locales() as $lang) {
            $this->pendingFiles[$lang] = [];
            $this->pendingDeleteMedia[$lang] = [];
        }
        if ($this->exists) {
            $this->fill(LocalesHelper::transformToProperties($resource->getTranslationsArray()));
        }
    }

    public function render()
    {
        return $this->view()->title(($this->exists ? 'Update' : 'Create') . ' Resource');
    }

    public function save(): void
    {
        $fields = LocalesHelper::transformToModelFields($this->validate(), $this->resource->translatedAttributes);
        $this->resource->fill($fields);
        $this->resource->save();

        // Upload pending files and media files according to language
        foreach ($this->pendingFiles as $lang => $files) {
            if (!empty($files)) {
                $translation = $this->resource->translations()->where('locale', $lang)->first();
                if ($translation) {
                    foreach ($files as $file) {
                        $translation->addMedia($file->getRealPath())
                            ->usingFileName($file->getClientOriginalName())
                            ->toMediaCollection('resources');
                    }
                }
            }
        }
        // Delayed deletion of media files (put save to delete)
        foreach ($this->pendingDeleteMedia as $lang => $ids) {
            $translation = $this->resource->translations()->where('locale', $lang)->first();
            if ($translation) {
                foreach ($ids as $mediaId) {
                    $media = $translation->media()->where('id', $mediaId)->first();
                    if ($media) $media->delete();
                }
            }
        }
        // clear pending files and pending deletions
        foreach ($this->pendingFiles as $lang => $files) {
            $this->pendingFiles[$lang] = [];
        }
        foreach ($this->pendingDeleteMedia as $lang => $ids) {
            $this->pendingDeleteMedia[$lang] = [];
        }

        if ($this->exists)
        {
            $this->success('Resource was updated.');
        }
        else
        {
            $this->success(
                "Resource was created.",
                redirectTo: route('dashboard.resources.edit', ['resource' => $this->resource]));
        }
    }

    // add files to pending files
    public function updatedPendingFiles($value, $key)
    {
        // no need change
    }

    // delete pending file
    public function removePendingFile($lang, $idx)
    {
        if (isset($this->pendingFiles[$lang][$idx])) {
            array_splice($this->pendingFiles[$lang], $idx, 1);
        }
    }

    // mark media file for delayed deletion
    public function markDeleteMedia($mediaId, $language)
    {
        if (!in_array($mediaId, $this->pendingDeleteMedia[$language] ?? [])) {
            $this->pendingDeleteMedia[$language][] = $mediaId;
        }
    }

    public function with(): array
    {
        return [
            'locales' => $this->locales(),
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
    <x-header :title="__('Resources Centre')" separator>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <x-tabs wire:model="selectedLanguage" label-div-class="border-b-[length:var(--border)] border-b-base-content/10 flex flex-wrap overflow-x-auto">
                @foreach ($locales as $language)
                    <x-tab :name="$language" :label="__('languages.' . $language)" class="pb-0">
                        <x-input label="Title" wire:model="title.{{ $language }}" />
                        <div class="mt-2">
                            <div class="font-semibold mb-1">{{ __('Existing Files') }}</div>
                            @php
                                $translation = $resource->translations->where('locale', $language)->first();
                            @endphp
                            @if($exists && $translation && $translation->getMedia('resources')->count())
                                <ul class="list-disc pl-5">
                                    @foreach($translation->getMedia('resources') as $media)
                                        @php
                                            $isPendingDelete = in_array($media->id, $pendingDeleteMedia[$language] ?? []);
                                        @endphp
                                        <li class="flex items-center gap-2 @if($isPendingDelete) opacity-50 line-through @endif">
                                            <a href="{{ $media->getFullUrl() }}" target="_blank" class="text-primary underline">{{ $media->file_name }}</a>
                                            @if(!$isPendingDelete)
                                                <x-button icon="fal.trash" size="xs" class="btn-ghost btn-xs" wire:click="markDeleteMedia({{ $media->id }}, '{{ $language }}')" :tooltip="__('Delete file (pending)')" spinner />
                                            @else
                                                <span class="text-xs text-red-500">{{ __('Pending delete') }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-500">No files uploaded.</span>
                            @endif
                        </div>
                        <div class="mt-2">
                            <x-input type="file" label="Files" wire:model="pendingFiles.{{ $language }}" multiple accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/*" />
                            @if(!empty($pendingFiles[$language]))
                                <div class="mt-2">
                                    <div class="font-semibold mb-1">Files to be uploaded:</div>
                                    <ul class="list-disc pl-5">
                                        @foreach($pendingFiles[$language] as $idx => $file)
                                            <li class="flex items-center gap-2">
                                                {{ $file->getClientOriginalName() }}
                                                <x-button icon="fal.trash" size="xs" class="btn-ghost btn-xs" wire:click="removePendingFile('{{ $language }}', {{ $idx }})" :tooltip="__('Remove from upload')" spinner />
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </x-tab>
                @endforeach
            </x-tabs>
            <x-slot:actions>
                <x-button label="Cancel" :link="route('dashboard.resources.list')" />
                <x-button :label="($exists ? 'Save' : 'Create')" :icon="'fal.' . ($exists ? 'floppy-disk' : 'plus')" type="submit" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
