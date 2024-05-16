<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperComment
 */
class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'content', 'post_id', 'user_id'
    ];

    function post () : BelongsTo {
        return $this->belongsTo(Post::class);
    }

    function user () : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
