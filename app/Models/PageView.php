<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'user_id',
        'ip_address',
        'user_agent'
    ];

    /**
     * Get the user associated with the page view.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}