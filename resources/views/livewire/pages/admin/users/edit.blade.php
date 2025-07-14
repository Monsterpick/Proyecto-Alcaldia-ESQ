<?php
use App\Models\User;
use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\WithFileUploads;

new class extends Component {

    use WithFileUploads;

    public function rendering(View $view)
    {
        $view->title('Editar Usuario');
    }

    public $name;
    public $last_name;
    public $document;
    public $phone;
    public $email;
    public $image;
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

    public function save() {
        $validated = $this->validate([
            'name' => 'required',
            'last_name' => 'required',
            'document' => 'required',
            'phone' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user->id)],
            'password' => $this->password ? 'min:8|confirmed' : '',
            'selectedRoles' => 'required|array|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);
        

        if($this->image && $this->image_url != 'images/no_user_image.png' && $this->image_url != null){
            Storage::delete($this->user['image_url']);
            $this->user['image_url'] = $this->image->store('images/users', 'public');
        }

        if($this->image){
            $this->image_url = $this->image->store('images/users', 'public');
        }

        $this->user->update([
            'name' => $this->name,
            'last_name' => $this->last_name,
            'document' => $this->document,
            'phone' => $this->phone,
            'email' => $this->email,
            'image_url' => $this->image_url,
        ]);

        if ($this->password) {
            $this->user->update(['password' => Hash::make($this->password)]);
        }

        $this->user->syncRoles($this->selectedRoles);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario actualizado',
            'text' => 'El usuario se ha actualizado correctamente',
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
                'name' => $this->user->name . ' ' . $this->user->last_name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <x-card>
            <form wire:submit.prevent="save">
                <h1 class="text-2xl font-bold">
                    Información Personal
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 py-4">
                    Actualice la información de la cuenta y la dirección de correo electrónico.
                </p>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                @include('livewire.pages.admin.users.partials.form', ['showPassword' => true, 'editForm' => true])

                                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button info wire:click="save" spinner="save" label="Actualizar" icon="check"
                            interaction="positive" />
                        <x-button slate label="Cancelar" icon="x-mark" interaction="secondary" wire:click="cancel" />
                    </div>
                </x-slot>
            </form>
        </x-card>
    </x-container>
</div>
