<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'post_comments';
    protected $primaryKey = 'comment_id';
    public $incrementing = true;
    public $keyType = 'int';

    protected $fillable = [
        'user_id',
        'post_id',
        'content'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
} 