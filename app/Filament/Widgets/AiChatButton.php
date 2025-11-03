<?php

namespace App\Filament\Widgets;

use Filament\Actions\Action as ActionsAction;
use Filament\Widgets\Widget;
use Filament\Notifications\Notification;
use Filament\Widgets\Actions\Action; // تأكد من الاستدعاء في الأعلى

class AiChatButton extends Widget
{
    //use Forms\Concerns\InteractsWithForms;

    protected string $view = 'filament.widgets.ai-chat-button';
    public array $messages = [];

    public string $newMessage = '';

    protected static ?int $sort = -1;

    public ?string $message = '';
    public array $chatLog = [];
    public bool $isModalOpen = false;




    public function sendMessage()
    {
        if (! $this->message) {
            return;
        }

        $reply = "AI reply to: " . $this->message;

        $this->chatLog[] = [
            'user' => $this->message,
            'ai' => $reply,
        ];

        $this->message = '';

        Notification::make()
            ->title('تم الإرسال')
            ->success()
            ->send();
            
    }


    
   
}
