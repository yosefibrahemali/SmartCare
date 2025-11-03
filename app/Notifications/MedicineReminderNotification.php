<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MedicineReminderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public $reminder_id;
    public $prescription;

    /**
     * Get the mail representation of the notification.
     */
    
    public function toMail($notifiable)
    {
        $confirmUrl = route('reminder.confirm', ['reminder' => $this->reminder_id]);
        $delayUrl = route('reminder.delay', ['reminder' => $this->reminder_id]);

        return (new MailMessage)
            ->subject('تذكير بتناول الدواء 💊')
            ->line('تذكير بتناول الدواء: ' . $this->prescription->medicine_name)
            ->line('الجرعة: ' . $this->prescription->dose)
            ->action('✅ تمّ التناول', $confirmUrl)
            ->line('أو يمكنك تأجيل التذكير لمدة 5 دقائق.')
            ->action('⏰ ذكّرني لاحقًا', $delayUrl)
            ->line('نتمنى لك دوام الصحة والعافية 🌿');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
