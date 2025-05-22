<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PostRejected extends Notification
{
    use Queueable;

    protected $post;
    protected $reason;

    public function __construct($post, $reason)
    {
        $this->post = $post;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bài viết của bạn đã bị từ chối')
            ->greeting('Xin chào ' . $notifiable->name)
            ->line('Bài viết "' . $this->post->title . '" của bạn đã bị từ chối.')
            ->line('Lý do: ' . $this->reason)
            ->line('Bạn có thể chỉnh sửa và gửi lại bài viết.')
            ->action('Chỉnh sửa bài viết', url('/posts/' . $this->post->id . '/edit'))
            ->line('Cảm ơn bạn đã đóng góp nội dung cho chúng tôi!');
    }

    public function toArray($notifiable)
    {
        return [
            'post_id' => $this->post->id,
            'title' => $this->post->title,
            'message' => 'Bài viết của bạn đã bị từ chối',
            'reason' => $this->reason,
            'type' => 'post_rejected'
        ];
    }
}
