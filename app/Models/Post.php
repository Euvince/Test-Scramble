<?php

namespace App\Models;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperPost
 */
class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'excerpt', 'content', 'user_id'
    ];

    function comments () : HasMany {
        return $this->hasMany(Comment::class);
    }

    function user () : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
