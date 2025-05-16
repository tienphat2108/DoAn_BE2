<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\Comment;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'status',
        'user_id',
        'scheduled_at',
        'shares_count'
    ];

    protected $attributes = [
        'shares_count' => 0
    ];

    protected $dates = [
        'scheduled_at',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'post_id');
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\PostComment::class, 'post_id');
    }

    public function views()
    {
        return $this->hasMany('App\\Models\\PostView', 'post_id');
    }
}
