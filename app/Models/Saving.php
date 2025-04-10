<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Saving extends Model
{
    protected $fillable = ['user_id', 'name', 'amount', 'date', 'description'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}