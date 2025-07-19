<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use SoftDeletes;
    protected $fillable = ['appointment_id', 'diagnosis', 'treatment', 'notes', 'prescriptions'];

    protected $casts = [
        'prescriptions' => 'json',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
