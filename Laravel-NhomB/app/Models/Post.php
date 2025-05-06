<?php
<<<<<<< HEAD

=======
>>>>>>> VuVanTri
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PostLike;
use App\Models\PostComment;

class Post extends Model
{
    use HasFactory;
<<<<<<< HEAD

    protected $fillable = [
        'image',
        'title',
        'status',
        'user_id',
        'scheduled_at'
    ];

    protected $dates = [
        'scheduled_at',
        'created_at',
        'updated_at'
    ];
=======
    protected $fillable = ['title', 'content'];
>>>>>>> VuVanTri

    public function user()
    {
        return $this->belongsTo(User::class);
    }
<<<<<<< HEAD

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
        return $this->hasMany(PostComment::class, 'post_id');
    }
} 
=======
}
>>>>>>> VuVanTri
