<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperRole
 */
class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    public function users () : BelongsToMany {
        return $this->belongsToMany(User::class);
    }
}
