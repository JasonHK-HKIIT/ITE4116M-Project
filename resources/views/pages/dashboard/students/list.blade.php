<?php

use App\Models\Campus;
use App\Models\Institute;
use App\Models\Student;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use Toast, WithPagination;

    public array $sortBy = ['column' => 'username', 'direction' => 'asc'];

    public int $perPage = 10;

    public bool $isDrawerOpened = false;

    #[Url(as: 'search')]
    public ?string $keywords = null;

    #[Url]
    public $institute_id = null;

    #[Url]
    public $campus_id = null;

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'username', 'label' => 'Student ID', 'class' => 'w-fit min-w-36 text-nowrap', 'sortable' => true],
            ['key' => 'full_name', 'label' => 'Name', 'class' => 'w-auto min-w-56', 'sortable' => true],
            ['key' => 'institute', 'label' => 'Institute', 'class' => 'w-fit min-w-48 text-nowrap', 'sortable' => false],
            ['key' => 'campus', 'label' => 'Campus', 'class' => 'w-fit min-w-36 text-nowrap', 'sortable' => false],
            ['key' => 'classes', 'label' => 'Classes', 'class' => 'w-fit min-w-40', 'sortable' => false],
            ['key' => 'programmes', 'label' => 'Programmes', 'class' => 'w-fit min-w-40', 'sortable' => false],
        ];
    }

    #[Computed]
    public function institutes(): array
    {
        return Institute::query()
            ->orderByTranslation('name', 'asc')
            ->get()
            ->map(fn ($institute) => ['id' => $institute->id, 'name' => $institute->name])
            ->toArray();
    }

    #[Computed]
    public function campuses(): array
    {
        return Campus::query()
            ->when($this->institute_id, function ($query, $instituteId)
            {
                $query->whereHas('institutes', fn ($query) => $query->where('institutes.id', $instituteId));
            })
            ->orderByTranslation('name', 'asc')
            ->get()
            ->map(fn ($campus) => ['id' => $campus->id, 'name' => $campus->name])
            ->toArray();
    }

    public function students(): LengthAwarePaginator
    {
        $useChineseName = str_starts_with(app()->getLocale(), 'zh');

        $sortColumns = [
            'username' => 'users.username',
            'full_name' => ($useChineseName ? 'users.chinese_name' : 'users.family_name'),
        ];

        $sortColumn = $sortColumns[$this->sortBy['column']] ?? 'users.username';

        return Student::query()
            ->select('students.*')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->with(['user', 'institute', 'campus', 'classes.programme'])
            ->when($this->keywords, function ($query, $keywords)
            {
                $query->where(function ($query) use ($keywords)
                {
                    $query
                        ->where('users.username', 'like', "%{$keywords}%")
                        ->orWhere('users.family_name', 'like', "%{$keywords}%")
                        ->orWhere('users.given_name', 'like', "%{$keywords}%")
                        ->orWhere('users.chinese_name', 'like', "%{$keywords}%");
                });
            })
            ->when($this->institute_id, function ($query, $instituteId)
            {
                $query->where('students.institute_id', $instituteId);
            })
            ->when($this->campus_id, function ($query, $campusId)
            {
                $query->where('students.campus_id', $campusId);
            })
            ->orderBy($sortColumn, $this->sortBy['direction'])
            ->orderBy('users.given_name', $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    public function updatedInstituteId($value): void
    {
        if (!$value)
        {
            $this->campus_id = null;

            return;
        }

        $campusIds = collect($this->campuses())->pluck('id')->all();

        if (!in_array((int) $this->campus_id, $campusIds, true))
        {
            $this->campus_id = null;
        }
    }

    public function deleteStudent(int $id): void
    {
        try
        {
            if (Student::destroy($id) > 0)
            {
                $this->success('Student was deleted.');
            }
        }
        catch (QueryException $exception)
        {
            $this->error('Student cannot be deleted because it is in use.');
        }
    }

    public function clear(): void
    {
        $this->reset([
            'keywords',
            'institute_id',
            'campus_id',
        ]);
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'students' => $this->students(),
            'institutes' => $this->institutes(),
            'campuses' => $this->campuses(),
        ];
    }
}; ?>

<div>
    <x-header :title="__('Students')" :subtitle="__('Student Management')" separator>
        <x-slot:middle class="justify-end! max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('Search by ID, name, or mobile...')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive />
            <x-button icon="fal.plus" class="btn-primary" :link="route('dashboard.students.create')" responsive />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$students" :sort-by="$sortBy">
            @scope('cell_username', $student)
                {{ $student->user?->username ?? '-' }}
            @endscope

            @scope('cell_full_name', $student)
                @php
                    $isChineseLocale = str_starts_with(app()->getLocale(), 'zh');
                    $englishName = trim(($student->user?->family_name ?? '') . ' ' . ($student->user?->given_name ?? ''));
                    $chineseName = trim($student->user?->chinese_name ?? '');
                    $displayName = $isChineseLocale ? ($chineseName ?: $englishName) : ($englishName ?: $chineseName);
                @endphp

                {{ $displayName ?: '-' }}
            @endscope

            @scope('cell_institute', $student)
                @if ($student->institute)
                    <x-badge :value="$student->institute->name" class="badge-sm badge-soft text-nowrap" />
                @else
                    <span>-</span>
                @endif
            @endscope

            @scope('cell_campus', $student)
                @if ($student->campus)
                    <x-badge :value="$student->campus->name" class="badge-sm badge-soft text-nowrap" />
                @else
                    <span>-</span>
                @endif
            @endscope

            @scope('cell_classes', $student)
                @forelse ($student->classes as $class)
                    <x-badge :value="$class->class_code . ' (' . $class->academic_year . ')'" class="badge-sm badge-soft text-nowrap" />
                @empty
                    <span>-</span>
                @endforelse
            @endscope

            @scope('cell_programmes', $student)
                @php
                    $programmes = $student->classes->pluck('programme')->filter()->unique('id');
                @endphp

                @forelse ($programmes as $programme)
                    <x-badge :value="$programme->programme_code" class="badge-sm badge-soft text-nowrap" />
                @empty
                    <span>-</span>
                @endforelse
            @endscope

            @scope('actions', $student)
                <div class="hidden lg:inline-flex flex-row w-8 lg:w-17">
                    <x-button icon="fal.pen-to-square" :tooltip="__('Edit')" :link="route('dashboard.students.edit', ['student' => $student])" class="btn-ghost btn-square btn-sm" />
                    <x-button icon="fal.trash" :tooltip="__('Delete')" wire:click="deleteStudent({{ $student->id }})" spinner class="btn-ghost btn-square btn-sm" />
                </div>

                <x-dropdown right>
                    <x-slot:trigger>
                        <x-button icon="fal.ellipsis-vertical" class="btn-ghost btn-square btn-sm lg:hidden" />
                    </x-slot:trigger>

                    <x-menu-item title="Edit" icon="fal.pen-to-square" :link="route('dashboard.students.edit', ['student' => $student])" />
                    <x-menu-item title="Delete" icon="fal.trash" wire:click.stop="deleteStudent({{ $student->id }})" spinner />
                </x-dropdown>
            @endscope
        </x-table>

        <x-pagination :rows="$students" wire:model.live="perPage" :per-page-values="[5, 10, 25]" />
    </x-card>

    <x-drawer wire:model="isDrawerOpened" title="Filters" right separator with-close-button class="w-3/5 md:w-1/2 lg:w-1/3">
        <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" :placeholder="__('Search by ID, name, or mobile...')" />
        <x-select label="Institute" wire:model.live="institute_id" :options="$institutes" placeholder="Any" />
        <x-select label="Campus" wire:model.live="campus_id" :options="$campuses" placeholder="Any" />

        <x-slot:actions>
            <x-button label="Reset" icon="fal.xmark" wire:click="clear" spinner />
            <x-button label="Done" icon="fal.check" class="btn-primary" @click="$wire.isDrawerOpened = false" />
        </x-slot:actions>
    </x-drawer>
</div>
