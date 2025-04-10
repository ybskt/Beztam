<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Expense extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'name', 'amount',
        'date', 'description', 'is_monthly'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function recurrences(): MorphMany
    {
        return $this->morphMany(MonthlyRecurrence::class, 'recurrable');
    }
}