<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TakeMedicineNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $reminder;

    public function __construct($reminder)
    {
        $this->reminder = $reminder;
    }

    // القنوات (يمكن إضافة 'fcm' لاحقًا)
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $confirmUrl = route('reminder.confirm', ['reminder' => $this->reminder->id]);
        $delayUrl = route('reminder.delay', ['reminder' => $this->reminder->id]);

        return (new MailMessage)
            ->subject('تذكير بتناول الدواء 💊')
            ->line('الدواء: ' . $this->reminder->medication->name)
            ->line('الجرعة: ' . $this->reminder->medication->dosage)
            ->line('يرجى تأكيد تناولك للدواء أو تأجيل التذكير.')
            ->action('✅ تم التناول', $confirmUrl)
            ->action('⏰ تأجيل 5 دقائق', $delayUrl);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'تذكير بتناول الدواء',
            'medicine' => $this->reminder->medication->name,
            'dose' => $this->reminder->medication->dosage,
            'reminder_id' => $this->reminder->id,
        ];
    }
}