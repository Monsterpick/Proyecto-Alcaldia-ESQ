<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Appointment extends Model
{
    use SoftDeletes;
    protected $fillable = ['patient_id', 'doctor_id', 'date', 'start_time', 'end_time', 'duration', 'reason', 'appointment_status_id'];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointmentStatus()
    {
        return $this->belongsTo(AppointmentStatus::class);
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    public function isEditable()
    {
        return $this->appointmentStatus->name != 'Cancelada' && $this->appointmentStatus->name != 'Completada';
    }

    /* Accesores */

    public function start(): Attribute
    {
        return Attribute::make(
            get: function(){
                $date = $this->date->format('Y-m-d');
                $time = $this->start_time->format('H:i:s');
                //return $date . ' ' . $time;
            /* Retornar en formato ISO 8601 */
            return Carbon::parse("{$date} {$time}")->toIso8601String();
            }
        );
    }

    public function end(): Attribute
    {
        return Attribute::make(
            get: function(){
                $date = $this->date->format('Y-m-d');
                $time = $this->end_time->format('H:i:s');
                //return $date . ' ' . $time;
            /* Retornar en formato ISO 8601 */
            return Carbon::parse("{$date} {$time}")->toIso8601String();
            }
        );
    }
}
