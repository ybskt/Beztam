<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Budget extends Model
{
    protected $fillable = [
        'user_id', 'name', 'amount', 'date', 
        'description', 'is_monthly', 'apply_saving'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recurrences(): MorphMany
    {
        return $this->morphMany(MonthlyRecurrence::class, 'recurrable');
    }
}