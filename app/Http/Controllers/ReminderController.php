<?php

namespace App\Http\Controllers;

use App\Models\MedicationReminder;
use App\Notifications\PatientDidNotTakeMedicine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ReminderController extends Controller
{
    public function confirm(MedicationReminder $reminder)
    {
        $reminder->update([
            'status' => 'confirmed',
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'تم تأكيد تناول الدواء ✅']);
    }

    public function delay(MedicationReminder $reminder)
    {
        $reminder->update([
            'status' => 'snoozed',
            'notify_at' => Carbon::now()->addMinutes(5),
        ]);

        return response()->json(['message' => 'تم تأجيل التذكير لمدة 5 دقائق ⏰']);
    }
}