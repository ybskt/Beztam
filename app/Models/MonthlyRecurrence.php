<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MonthlyRecurrence extends Model
{
    protected $fillable = ['next_occurrence', 'occurrence_count'];

    public function recurrable(): MorphTo
    {
        return $this->morphTo();
    }
}