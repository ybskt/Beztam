<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'amount',
        'free_amount',
        'date',
        'description'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'free_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function calculateFreeAmount($amount, $savingsRate, $applySaving)
    {
        return $applySaving 
            ? $amount * (1 - ($savingsRate / 100))
            : $amount;
    }
}