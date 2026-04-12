<?php

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

new
#[Layout('layouts::portal')]
class extends Component
{
    use PasswordValidationRules;
    use Toast;

    #[Validate('required|current_password')]
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function render()
    {
        return $this->view()->title(trans('system.password.user.title'));
    }

    protected function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => $this->passwordRules(),
        ];
    }

    public function updatePassword(): void
    {
        $fields = $this->validate();

        Auth::user()?->update([
            'password' => $fields['password'],
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->success(trans('system.password.user.messages.updated'));
    }
}; ?>

<div class="p-4 md:p-8">
    <x-header :title="__('system.password.user.title')" :subtitle="__('system.password.user.subtitle')" separator />

    <x-card shadow>
        <x-form wire:submit="updatePassword">
            <div class="grid gap-5 md:grid-cols-2">
                <x-input
                    :label="__('system.password.user.form.current_password')"
                    wire:model="current_password"
                    type="password"
                    autocomplete="current-password"
                    icon="fal.lock"
                />

                <div class="hidden md:block"></div>

                <x-input
                    :label="__('system.password.user.form.new_password')"
                    wire:model="password"
                    type="password"
                    autocomplete="new-password"
                    icon="fal.key"
                />

                <x-input
                    :label="__('system.password.user.form.repeat_new_password')"
                    wire:model="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    icon="fal.key"
                />
            </div>

            <x-slot:actions>
                <x-button :label="__('actions.change_password')" type="submit" class="btn-primary" spinner="updatePassword" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
