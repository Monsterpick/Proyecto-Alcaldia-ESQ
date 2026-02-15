<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Doctor;
use Carbon\CarbonPeriod;

class AppointmentService
{
    /* 
    Busca disponibilidad de citas para un doctor, especialidad y fecha específica.
    @param string $date Fecha de la cita
    @param string $hour Hora de la cita
    @param int $speciality_id ID de la especialidad
    @return \Illuminate\Database\Eloquent\Collection
    */
    public function searchAvailability($date, $hour, $speciality_id)
    {
        //Convertir la fecha y hora a formato Carbon
        $date = Carbon::parse($date);
        $hourStart = Carbon::parse($hour)->format('H:i:s');
        $hourEnd = Carbon::parse($hour)->addHour()->format('H:i:s');

        //Buscar doctores con horarios disponibles
        $doctors = Doctor::whereHas('schedules', function($query) use ($date, $hourStart, $hourEnd) {
            $query->where('day_of_week', $date->dayOfWeek)
                ->where('start_time', '>=', $hourStart)
                ->where('start_time', '<', $hourEnd);
        })
        //Filtrar por especialidad si se proporciona
        ->when($speciality_id, function($query, $speciality_id){
            return $query->where('speciality_id', $speciality_id);
        })
        //Cargar relaciones necesarias
        ->with([
            'user',
            'speciality',
            'schedules' => function($query) use ($date, $hourStart, $hourEnd){
                //Filtrar horarios disponibles para el día y hora específicas
                $query->where('day_of_week', $date->dayOfWeek)
                ->where('start_time', '>=', $hourStart)
                ->where('start_time', '<', $hourEnd);
            },
            //Filtrar citas existentes para el día y hora específicas
            'appointments' => function($query) use ($date, $hourStart, $hourEnd)
            {
                //Filtrar citas para la fecha y hora específicas
                $query->whereDate('date', $date)
                ->where('start_time', '>=', $hourStart)
                ->where('start_time', '<', $hourEnd);
            }
        ])
        //Obtener los resultados
        ->get();

        return $result = $this->processResults($doctors);

    }

    public function processResults($doctors){

        /* La variable map lo que hace es recorrer el array de doctores y aplicar la función a cada elemento o transformarlo en un nuevo array */
        return $doctors->mapWithKeys(function($doctor){

            $schedules = $this->getAvailableSchedules($doctor->schedules, $doctor->appointments);

            /* Comprobamos si el doctor que estamos pasando tiene por lo menos un horario disponible, sino pasa un array vacio */
            return $schedules->contains('disabled', false) ?
            [ $doctor->id => [
                'doctor' => $doctor,
                'schedules' => $schedules,
            ]] : [];
        });
    }

    public function getAvailableSchedules($schedules, $appointments)
    {
        return $schedules->map(function($schedule) use ($appointments){

            /* Some recorre todo el array y devuelve true si encuentra un elemento que cumple la condición */
            $isBooked = $appointments->some(function($appointment) use ($schedule){
                $appointmentPeriod = CarbonPeriod::create(
                    $appointment->start_time,
                    config('schedule.appointment_duration') . ' minutes',
                    $appointment->end_time
                )->excludeEndDate();

                return $appointmentPeriod->contains($schedule->start_time);
                
            });

            return [
                'start_time' => $schedule->start_time->format('H:i'),
                'disabled' => $isBooked,
            ];
        });
    }
}