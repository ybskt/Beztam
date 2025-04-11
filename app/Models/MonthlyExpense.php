<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyExpense extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'amount',
        'day_of_month',
        'description' ,
        'occurrences'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'day_of_month' => 'integer' ,
        'occurrences' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}