<?php

use App\Actions\Fortify\PasswordValidationRules;
use App\Enums\Role;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use PasswordValidationRules;
    use Toast;

    #[Validate('required|string')]
    public string $username = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function render()
    {
        return $this->view()->title(trans('system.password.admin.title'));
    }

    protected function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => $this->passwordRules(),
        ];
    }

    public function resetPassword(): void
    {
        $fields = $this->validate();

        $user = User::query()
            ->where('username', $fields['username'])
            ->first();

        if (!$user)
        {
            $this->addError('username', trans('system.password.admin.messages.user_not_found'));

            return;
        }

        if ($user->hasRole(Role::ADMIN))
        {
            $this->addError('username', trans('system.password.admin.messages.admin_not_allowed'));

            return;
        }

        $user->update([
            'password' => $fields['password'],
        ]);

        $this->reset(['password', 'password_confirmation']);
        $this->success(trans('system.password.admin.messages.updated'));
    }
}; ?>

<div>
    <x-header :title="__('system.password.admin.title')" :subtitle="__('system.password.admin.subtitle')" separator />

    <x-card shadow>
        <x-form wire:submit="resetPassword">
            <div class="grid gap-5 md:grid-cols-2">
                <x-input
                    :label="__('system.password.admin.form.username')"
                    wire:model="username"
                    icon="fal.user"
                    placeholder="student-john"
                />

                <div class="hidden md:block"></div>

                <x-input
                    :label="__('system.password.admin.form.new_password')"
                    wire:model="password"
                    type="password"
                    autocomplete="new-password"
                    icon="fal.key"
                />

                <x-input
                    :label="__('system.password.admin.form.repeat_new_password')"
                    wire:model="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    icon="fal.key"
                />
            </div>

            <x-slot:actions>
                <x-button :label="__('actions.reset_password')" type="submit" class="btn-primary" spinner="resetPassword" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
