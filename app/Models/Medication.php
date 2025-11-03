<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $fillable = [
        'patient_id',
        'name',
        'description',
        'dosage',
        'times_per_day'
        
    ];
    
     // Medication.php
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function schedules()
    {
        return $this->hasMany(MedicationSchedule::class);
    }

    public function reminders()
    {
        return $this->hasMany(MedicationReminder::class);
    }
}
