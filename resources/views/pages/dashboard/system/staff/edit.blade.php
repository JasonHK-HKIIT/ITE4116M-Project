<?php

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use Toast;

    public bool $exists = false;

    public User $staff;

    #[Validate('required')]
    public string $username = '';

    #[Validate('required')]
    public string $family_name = '';

    #[Validate('required')]
    public string $given_name = '';

    public ?string $chinese_name = null;

    #[Validate('required')]
    public string $role = Role::STAFF->value;

    #[Validate([
        'permission_keys' => ['nullable', 'array'],
        'permission_keys.*' => ['required', 'string', 'distinct'],
    ], as: 'permissions')]
    public array $permission_keys = [];

    public function mount(User $staff): void
    {
        $this->exists = $staff->exists;
        $this->staff = $staff;

        if ($this->exists && $staff->hasRole(Role::STUDENT))
        {
            abort(404);
        }

        if ($this->exists)
        {
            $staff->load('permissions');

            $this->fill([
                'username' => $staff->username,
                'family_name' => $staff->family_name,
                'given_name' => $staff->given_name,
                'chinese_name' => $staff->chinese_name,
                'role' => $staff->role->value,
            ]);

            $this->permission_keys = $staff->permissions
                ->map(fn ($permission) => $permission->permission->value)
                ->values()
                ->toArray();
        }
    }

    public function render()
    {
        return $this->view()->title(($this->exists ? 'Update' : 'Create') . ' Staff Member');
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

    protected function rules(): array
    {
        $userId = $this->staff->id;

        return [
            'username' => ['required', 'alpha_dash:ascii', 'max:255', Rule::unique('users', 'username')->ignore($userId)],
            'family_name' => ['required', 'max:255'],
            'given_name' => ['required', 'max:255'],
            'chinese_name' => ['nullable', 'max:255'],
            'role' => ['required', Rule::in([Role::STAFF->value, Role::ADMIN->value])],
            'permission_keys.*' => ['required', Rule::in(Permission::values())],
        ];
    }

    public function updatedRole($value): void
    {
        if ($value === Role::ADMIN->value)
        {
            $this->permission_keys = [];
        }
    }

    public function save(): void
    {
        $fields = $this->validate();
        $wasExisting = $this->exists;

        if ($wasExisting && (int) auth()->id() === $this->staff->id && $fields['role'] !== Role::ADMIN->value)
        {
            $this->addError('role', 'You cannot change your own account role from Admin.');

            return;
        }

        $user = $wasExisting ? $this->staff : new User();
        $user->username = $fields['username'];
        $user->family_name = $fields['family_name'];
        $user->given_name = $fields['given_name'];
        $user->chinese_name = $fields['chinese_name'];
        $user->role = $fields['role'];

        if (!$wasExisting)
        {
            $user->password = Str::random(16);
        }

        $user->save();

        $user->permissions()->delete();

        if ($fields['role'] === Role::STAFF->value)
        {
            foreach ($fields['permission_keys'] ?? [] as $permission)
            {
                $user->permissions()->create(['permission' => $permission]);
            }
        }

        $this->staff = $user;
        $this->exists = true;

        if ($wasExisting)
        {
            $this->success('Staff member was updated.');
        }
        else
        {
            $this->success(
                'Staff member was created. A password has been auto-generated.',
                redirectTo: route('dashboard.system.staff.edit', ['staff' => $user])
            );
        }
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
            'roleOptions' => $this->roleOptions(),
            'permissionOptions' => $this->permissionOptions(),
        ];
    }
}; ?>

<div>
    <x-header :title="__('Staff Members')" :subtitle="($exists ? 'Update' : 'Create') . ' Staff Member'" separator>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <div class="grid gap-5 md:grid-cols-2">
                <x-input label="Username" wire:model="username" placeholder="staff-john" />
                <x-input label="Chinese Name" wire:model="chinese_name" placeholder="Optional" />
                <x-input label="Family Name" wire:model="family_name" />
                <x-input label="Given Name" wire:model="given_name" />
                <x-select label="Role" wire:model.live="role" :options="$roleOptions" />

                <div class="md:col-span-2">
                    <x-choices
                        label="Permissions"
                        wire:model="permission_keys"
                        :options="$permissionOptions"
                        :disabled="$role === App\Enums\Role::ADMIN->value"
                    />
                    @if ($role === App\Enums\Role::ADMIN->value)
                        <p class="mt-1 text-sm text-base-content/70">Admin users always have full access. Individual permissions are disabled.</p>
                    @endif
                </div>
            </div>

            <x-slot:actions>
                <x-button label="Cancel" :link="route('dashboard.system.staff.list')" />
                <x-button :label="($exists ? 'Save' : 'Create')" :icon="'fal.' . ($exists ? 'floppy-disk' : 'plus')" type="submit" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
