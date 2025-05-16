<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    use HasFactory;

    protected $primaryKey = 'media_id';
    public $incrementing = true;

    protected $fillable = [
        'post_id',
        'file_url',
        'file_type'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
} 