<?php

namespace App\Jobs;

use App\Models\MedicationReminder;
use App\Notifications\PatientDidNotTakeMedicine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendRelativeAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reminderId;

    public function __construct($reminderId)
    {
        $this->reminderId = $reminderId;
    }

    public function handle()
    {
        $reminder = MedicationReminder::find($this->reminderId);

        if ($reminder && $reminder->status === 'pending') {
            $relative = $reminder->patient->relatives()->first(); // أو حسب من تحدده
            Notification::send($relative, new PatientDidNotTakeMedicine($reminder));
        }
    }
}