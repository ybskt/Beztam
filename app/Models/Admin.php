<?php

// app/Models/Admin.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $guard = 'admin';
    protected $fillable = ['password'];
    // Add to Admin model:
    // public function notifications()
    // {
    //     return $this->hasMany(Notification::class);
    // }
}
