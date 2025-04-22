<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'subject',
        'message',
        'is_read'
    ];

    // Helper method to mark as read
    public function markAsRead()
    {
        $this->update(['is_read' => 1]);
    }
}