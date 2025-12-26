<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
            'gender' => 'nullable|in:male,female,other',
            'bio' => 'nullable|string|max:2000',
        ];
    }
}
