<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'user_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * User relationship.
     * @return BelongsTo<User, Image>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
