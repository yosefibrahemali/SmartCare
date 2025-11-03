<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicationReminder extends Model
{
     protected $fillable = [
        'prescription_id',
        'patient_id',
        'relative_id',
        'notify_at',
        'status',
        'medication_name',
    ];


    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function relative()
    {
        return $this->belongsTo(User::class, 'relative_id');
    }
    
}
