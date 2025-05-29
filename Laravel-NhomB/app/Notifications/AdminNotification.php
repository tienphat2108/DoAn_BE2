<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;
    public $method;

    public function __construct($message, $method = 'database')
    {
        $this->message = $message;
        $this->method = $method;
    }

    public function via($notifiable)
    {
        if ($this->method === 'email') {
            return ['mail'];
        } else {
            return ['database'];
        }
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Thông báo từ Admin')
            ->line($this->message);
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'sent_at' => now(),
        ];
    }
} 