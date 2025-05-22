<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PostApproved extends Notification
{
    use Queueable;

    protected $post;

    public function __construct($post)
    {
        $this->post = $post;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bài viết của bạn đã được duyệt')
            ->greeting('Xin chào ' . $notifiable->name)
            ->line('Bài viết "' . $this->post->title . '" của bạn đã được duyệt.')
            ->line('Bạn có thể xem bài viết tại đây:')
            ->action('Xem bài viết', url('/posts/' . $this->post->id))
            ->line('Cảm ơn bạn đã đóng góp nội dung cho chúng tôi!');
    }

    public function toArray($notifiable)
    {
        return [
            'post_id' => $this->post->id,
            'title' => $this->post->title,
            'message' => 'Bài viết của bạn đã được duyệt',
            'type' => 'post_approved'
        ];
    }
}
