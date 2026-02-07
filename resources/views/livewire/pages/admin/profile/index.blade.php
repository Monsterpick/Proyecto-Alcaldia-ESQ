<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Mi Perfil');
    }


}; ?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Mi Perfil',
            ],
        ]" />
    </x-slot>

    <div class="space-y-6">

        <livewire:pages.admin.profile.photo />

        <livewire:pages.admin.profile.profile />
        
        <livewire:pages.admin.profile.password />
        
        <livewire:pages.admin.profile.delete />

    </div>

</div>
