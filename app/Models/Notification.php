<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false; // Completely disable timestamps
    
    protected $fillable = [
        'user_id',
        'notification', 
        'type',
        'created_at' // Manually handled
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}