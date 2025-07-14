<?php

use Livewire\Volt\Component;
use App\Models\Doctor;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use App\Models\BloodType;
use App\Models\Speciality;

new class extends Component {
    public function rendering(View $view)
    {
        $view->title('Horarios');
    }

    public $user;
    public $doctor;
    public $speciality_id;
    public $specialities;
    public $medical_license_number;
    public $medical_college_number;
    public $title;
    public $biography;
    public $image;
    public $is_active;

    public function mount(Doctor $doctor)
    {
        $this->specialities = Speciality::all();

        $this->doctor = $doctor;
        $this->user = $doctor->user;
        $this->speciality_id = $doctor->speciality_id;
        $this->medical_license_number = $doctor->medical_license_number;
        $this->medical_college_number = $doctor->medical_college_number;
        $this->title = $doctor->title;
        $this->biography = $doctor->biography;
        $this->image = $doctor->user->image_url;
        $this->is_active = $doctor->is_active ? '1' : '0';
    }

   

    public function cancel()
    {
        $this->redirect(route('admin.doctors.index'), navigate: true);
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
                'name' => 'Doctores',
                'route' => route('admin.doctors.index'),
            ],
            [
                'name' => $this->doctor->user->name,
            ],
        ]" />
    </x-slot>

    <x-container class="lg:py-0 lg:px-6">
        <livewire:pages.admin.doctors.schedulemanager :doctor="$doctor" />
    </x-container>

</div>
