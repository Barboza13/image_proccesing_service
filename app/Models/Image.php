<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'path',
        'format',
        'size',
        'resolution'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * User relationship.
     * @return BelongsToMany<User, Image>
     */
    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
