<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:5',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required!',
            'email.email' => 'Please provide a valid email address!',
            'password.required' => 'Password is required!',
            'password.min' => 'Password must be at least 5 characters long!',
        ];
    }
     
}
