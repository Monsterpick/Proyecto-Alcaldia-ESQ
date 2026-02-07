<?php

use App\Models\User;
use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;

new class extends Component {

    public function rendering(View $view)
    {
        $view->title('Usuario');
    }

    public $name;
    public $last_name;
    public $document;
    public $phone;
    public $email;
    public $image_url;
    public $password;
    public $password_confirmation;
    public $roles = [];
    public $selectedRoles = [];
    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->last_name = $user->last_name;
        $this->document = $user->document;
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->image_url = $user->image_url;
        $this->roles = Role::all();
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
    }


    public function cancel()
    {
        $this->redirect(route('admin.users.index'), navigate: true);
    }
}; ?>

<div>
    
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Usuarios',
                'route' => route('admin.users.index'),
            ],
            [
                'name' => $this->user->name . ' ' . $this->user->last_name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
                <h1 class="text-2xl font-bold">
                    Información Personal
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Actualice la información de la cuenta y la dirección de correo electrónico.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.users.partials.form', ['showPassword' => true, 'editForm' => true, 'showForm' => false])

                                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button slate label="Atras" icon="chevron-left" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
        </x-card>
    </x-container>
</div>
