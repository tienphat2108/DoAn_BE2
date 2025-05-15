<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostEditRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $post;
    public $editReason;

    public function __construct(Post $post, string $editReason)
    {
        $this->post = $post;
        $this->editReason = $editReason;
    }

    public function build()
    {
        return $this->subject('Yêu cầu chỉnh sửa bài viết')
                    ->view('emails.post-edit-request');
    }
} 