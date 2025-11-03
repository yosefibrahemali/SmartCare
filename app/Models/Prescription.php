<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Prescription extends Model
{
    protected $fillable = [
        'patient_id',
        'medications',
        'instructions',
        'frequency_per_day',
        'dosage',
        'duration_days',
        'start_date',
        'expiry_date',
        'notes',
        'status',
    ];


    

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // public function reminders()
    // {
    //     return $this->hasMany(MedicationReminder::class);
    // }

    protected static function booted()
    {
        
        static::creating(function ($prescription) {
            // حساب تاريخ الانتهاء تلقائيًا
            if (!$prescription->expiry_date && $prescription->start_date && $prescription->duration_days) {
                $prescription->expiry_date = Carbon::parse($prescription->start_date)
                    ->addDays($prescription->duration_days);
            }
        });

        static::created(function ($prescription) {
            // إنشاء تذكيرات عند إنشاء وصفة جديدة
            self::createMedicationReminders($prescription);
        });
    }

    protected static function createMedicationReminders($prescription)
    {
        $frequency = (int) $prescription->frequency_per_day ?: 1;
        $duration = (int) $prescription->duration_days ?: 1;
        $startDate = Carbon::parse($prescription->start_date);
        $endDate = Carbon::parse($prescription->expiry_date ?? $startDate->copy()->addDays($duration));
        $patientId = $prescription->patient_id;

        // توزيع التذكيرات في اليوم (24 ساعة / عدد المرات)
        $intervalHours = floor(24 / $frequency);

        for ($day = 0; $day < $duration; $day++) {
            $dayDate = $startDate->copy()->addDays($day);
            
            for ($i = 0; $i < $frequency; $i++) {
                $notifyAt = $dayDate->copy()->startOfDay()->addHours($i * $intervalHours);

                MedicationReminder::create([
                    'prescription_id' => $prescription->id,
                    'patient_id' => $patientId,
                    'notify_at' => $notifyAt,
                    'status' => 'pending',
                ]);
            }
        }
    }



    public function medicationReminders()
    {
        return $this->hasMany(MedicationReminder::class);
    }

}
