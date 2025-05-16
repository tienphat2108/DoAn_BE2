<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostHistory extends Model
{
    use HasFactory;

<<<<<<< HEAD:Laravel-NhomB/app/Models/PostComment.php
    protected $table = 'post_comments';
    protected $primaryKey = 'comment_id';
    public $incrementing = true;

=======
>>>>>>> origin/master:Laravel-NhomB/app/Models/PostHistory.php
    protected $fillable = [
        'post_id',
        'user_id',
        'action',
        'details'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 