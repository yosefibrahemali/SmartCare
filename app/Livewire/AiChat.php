<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Notifications\Notification;


class AiChat extends Component
{
     
    public $messages = [];
    public $input = '';

    public function sendMessage()
    {
        if (trim($this->input) === '') return;

        // أضف رسالة المستخدم
        $this->messages[] = [
            'text' => $this->input,
            'user' => true,
        ];

        $userMessage = $this->input;
        $this->input = '';

        // رد تلقائي (بشكل مؤقت)
        $reply = $this->getReply($userMessage);

        $this->messages[] = [
            'text' => $reply,
            'user' => false,
        ];
    }

    private function getReply($message)
    {
        $message = mb_strtolower($message);

        if (str_contains($message, 'ماء')) return 'تذكّر شرب الماء بانتظام 💧';
        if (str_contains($message, 'نوم')) return 'جيد أن تنام من 7 إلى 8 ساعات يومياً 😴';
        if (str_contains($message, 'غذاء')) return 'تناول وجبات غنية بالخضار والفاكهة 🥦🍎';
        if (str_contains($message, 'رياضة')) return 'حاول المشي يومياً 30 دقيقة 🏃‍♂️';
        return 'جميل جداً! كيف يمكنني مساعدتك أكثر؟ 😊';
    }

    public function render()
    {
        return view('livewire.ai-chat');
    }
}
