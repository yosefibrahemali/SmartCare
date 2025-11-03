<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PatientDidNotTakeMedicine extends Notification implements ShouldQueue
{
    use Queueable;

    public $reminder;

    public function __construct($reminder)
    {
        $this->reminder = $reminder;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('تنبيه: المريض لم يتناول الدواء')
            ->line('المريض ' . $this->reminder->patient->name . ' لم يؤكد تناول الدواء: ' . $this->reminder->medication->name)
            ->action('عرض التفاصيل', url('/reminders/' . $this->reminder->id));
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'المريض لم يتناول الدواء',
            'medicine' => $this->reminder->medication->name,
            'reminder_id' => $this->reminder->id,
        ];
    }
}