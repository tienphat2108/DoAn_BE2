<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;

    protected $table = 'post_comments'; // Đặt tên bảng nếu cần, hoặc sửa lại cho đúng

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
    ];

    // Quan hệ với Post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 