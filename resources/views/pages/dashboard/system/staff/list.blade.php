<?php

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\User;
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
    public ?string $role = null;

    #[Url]
    public ?string $permission = null;

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'username', 'label' => 'Username', 'class' => 'w-fit min-w-40 text-nowrap', 'sortable' => true],
            ['key' => 'full_name', 'label' => 'Name', 'class' => 'w-auto min-w-56', 'sortable' => true],
            ['key' => 'role', 'label' => 'Role', 'class' => 'w-fit min-w-28 text-nowrap', 'sortable' => true],
            ['key' => 'permissions', 'label' => 'Permissions', 'class' => 'w-fit min-w-64', 'sortable' => false],
            ['key' => 'created_at', 'label' => 'Created', 'class' => 'w-fit min-w-36 text-nowrap', 'sortable' => true],
        ];
    }

    #[Computed]
    public function roleOptions(): array
    {
        return [
            ['id' => Role::STAFF->value, 'name' => 'Staff'],
            ['id' => Role::ADMIN->value, 'name' => 'Admin'],
        ];
    }

    #[Computed]
    public function permissionOptions(): array
    {
        return collect(Permission::cases())
            ->map(fn (Permission $permission) => [
                'id' => $permission->value,
                'name' => $this->permissionLabel($permission->value),
            ])
            ->values()
            ->all();
    }

    public function staffMembers(): LengthAwarePaginator
    {
        $useChineseName = str_starts_with(app()->getLocale(), 'zh');

        $sortColumns = [
            'username' => 'users.username',
            'full_name' => ($useChineseName ? 'users.chinese_name' : 'users.family_name'),
            'role' => 'users.role',
            'created_at' => 'users.created_at',
        ];

        $sortColumn = $sortColumns[$this->sortBy['column']] ?? 'users.username';

        return User::query()
            ->from('users')
            ->with('permissions')
            ->whereIn('users.role', [Role::STAFF->value, Role::ADMIN->value])
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
            ->when($this->role, function ($query, $role)
            {
                $query->where('users.role', $role);
            })
            ->when($this->permission, function ($query, $permission)
            {
                $query->where(function ($query) use ($permission)
                {
                    $query
                        ->where('users.role', Role::ADMIN->value)
                        ->orWhereHas('permissions', fn ($query) => $query->where('permission', $permission));
                });
            })
            ->orderBy($sortColumn, $this->sortBy['direction'])
            ->orderBy('users.given_name', $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    public function deleteStaff(int $id): void
    {
        if ((int) auth()->id() === $id)
        {
            $this->error('You cannot delete your own account.');

            return;
        }

        try
        {
            $deletedRows = User::query()
                ->where('id', $id)
                ->whereIn('role', [Role::STAFF->value, Role::ADMIN->value])
                ->delete();

            if ($deletedRows > 0)
            {
                $this->success('Staff member was deleted.');
            }
        }
        catch (QueryException $exception)
        {
            $this->error('Staff member cannot be deleted because it is in use.');
        }
    }

    public function clear(): void
    {
        $this->reset([
            'keywords',
            'role',
            'permission',
        ]);
    }

    public function permissionLabel(string $permission): string
    {
        return match ($permission)
        {
            Permission::Calendar->value => 'Calendar',
            Permission::AcademicStructure->value => 'Academic Structure',
            Permission::StudentsManagement->value => 'Students Management',
            Permission::StudentActivities->value => 'Student Activities',
            Permission::NewsAnnouncement->value => 'News & Announcement',
            Permission::ResourcesCentre->value => 'Resource Centre',
            default => ucfirst($permission),
        };
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'staffMembers' => $this->staffMembers(),
            'roleOptions' => $this->roleOptions(),
            'permissionOptions' => $this->permissionOptions(),
        ];
    }
}; ?>

<div>
    <x-header :title="__('Staff Members')" :subtitle="__('System User Management')" separator>
        <x-slot:middle class="justify-end! max-md:hidden">
            <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" type="search" :placeholder="__('Search by username or name...')" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('Filters')" icon="fal.filter" @click="$wire.isDrawerOpened = true" responsive />
            <x-button icon="fal.plus" class="btn-primary" :link="route('dashboard.system.staff.create')" responsive />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$staffMembers" :sort-by="$sortBy">
            @scope('cell_username', $staff)
                {{ $staff->username }}
            @endscope

            @scope('cell_full_name', $staff)
                @php
                    $isChineseLocale = str_starts_with(app()->getLocale(), 'zh');
                    $englishName = trim(($staff->family_name ?? '') . ' ' . ($staff->given_name ?? ''));
                    $chineseName = trim($staff->chinese_name ?? '');
                    $displayName = $isChineseLocale ? ($chineseName ?: $englishName) : ($englishName ?: $chineseName);
                @endphp

                {{ $displayName ?: '-' }}
            @endscope

            @scope('cell_role', $staff)
                <x-badge
                    :value="ucfirst($staff->role->value)"
                    class="badge-sm text-nowrap"
                    :class="$staff->role === App\Enums\Role::ADMIN ? 'badge-primary text-primary-content' : 'badge-warning text-warning-content'"
                />
            @endscope

            @scope('cell_permissions', $staff)
                @if ($staff->role === App\Enums\Role::ADMIN)
                    <x-badge value="All Permissions" class="badge-sm badge-primary text-primary-content text-nowrap" />
                @elseif ($staff->permissions->isEmpty())
                    <span>-</span>
                @else
                    @foreach ($staff->permissions as $permission)
                        <x-badge :value="$this->permissionLabel($permission->permission->value)" class="badge-sm badge-soft text-nowrap" />
                    @endforeach
                @endif
            @endscope

            @scope('cell_created_at', $staff)
                {{ optional($staff->created_at)?->format('Y-m-d') ?? '-' }}
            @endscope

            @scope('actions', $staff)
                <div class="hidden lg:inline-flex flex-row w-8 lg:w-17">
                    <x-button icon="fal.pen-to-square" :tooltip="__('Edit')" :link="route('dashboard.system.staff.edit', ['staff' => $staff])" class="btn-ghost btn-square btn-sm" />
                    <x-button icon="fal.trash" :tooltip="__('Delete')" wire:click="deleteStaff({{ $staff->id }})" spinner class="btn-ghost btn-square btn-sm" />
                </div>

                <x-dropdown right>
                    <x-slot:trigger>
                        <x-button icon="fal.ellipsis-vertical" class="btn-ghost btn-square btn-sm lg:hidden" />
                    </x-slot:trigger>

                    <x-menu-item title="Edit" icon="fal.pen-to-square" :link="route('dashboard.system.staff.edit', ['staff' => $staff])" />
                    <x-menu-item title="Delete" icon="fal.trash" wire:click.stop="deleteStaff({{ $staff->id }})" spinner />
                </x-dropdown>
            @endscope
        </x-table>

        <x-pagination :rows="$staffMembers" wire:model.live="perPage" :per-page-values="[5, 10, 25]" />
    </x-card>

    <x-drawer wire:model="isDrawerOpened" title="Filters" right separator with-close-button class="w-3/5 md:w-1/2 lg:w-1/3">
        <x-input icon="fal.magnifying-glass" wire:model.live.debounce="keywords" :placeholder="__('Search by username or name...')" />
        <x-select label="Role" wire:model.live="role" :options="$roleOptions" placeholder="Any" />
        <x-select label="Permission" wire:model.live="permission" :options="$permissionOptions" placeholder="Any" />

        <x-slot:actions>
            <x-button label="Reset" icon="fal.xmark" wire:click="clear" spinner />
            <x-button label="Done" icon="fal.check" class="btn-primary" @click="$wire.isDrawerOpened = false" />
        </x-slot:actions>
    </x-drawer>
</div>
