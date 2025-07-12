<?php

use App\Models\User;
use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\WithFileUploads;

new class extends Component {

    use WithFileUploads;

    public $name;
    public $last_name;
    public $document;
    public $phone;
    public $email;
    public $image_url;
    public $image;
    public $password;
    public $password_confirmation;
    public $roles = [];
    public $selectedRoles = [];
    public $user;

    public function rendering(View $view)
    {
        $view->title('Crear Usuario');
    }

    public function mount()
    {
        $this->roles = Role::all();
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required',
            'last_name' => 'required',
            'document' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'selectedRoles' => 'required|array|min:1',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);

        $this->image_url = $this->image->store('images/users', 'public');

        $user = User::create([
            'name' => $this->name,
            'last_name' => $this->last_name,
            'document' => $this->document,
            'phone' => $this->phone,
            'email' => $this->email,
            'image_url' => $this->image_url ?? 'images/no_user_image.png',
            'password' => Hash::make($this->password),
        ]);
        
        
        $user->assignRole($this->selectedRoles);
        
        


        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario creado',
            'text' => 'El usuario se ha creado correctamente',
        ]);

        $this->redirect(route('admin.users.index'), navigate: true);
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
                'name' => 'Crear Usuario',
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit.prevent="save" enctype="multipart/form-data">
                <h1 class="text-2xl font-bold">
                    Información Personal
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Actualice la información de la cuenta y la dirección de correo electrónico.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.users.partials.form', ['showForm' => true, 'editForm' => false])

                                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button info wire:click="save" spinner="save" label="Guardar" icon="check"
                            interaction="positive" />
                        <x-button slate label="Cancelar" icon="x-mark" interaction="secondary"
                            wire:click="cancel" />
                    </div>
                </x-slot>
            </form>
        </x-card>
    </x-container>
</div>
