<?php

// app/Http/Requests/AdminLoginRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return ['password' => 'required|string'];
    }
}