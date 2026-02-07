<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentStatus extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'description'];
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
