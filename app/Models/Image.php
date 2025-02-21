<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
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
