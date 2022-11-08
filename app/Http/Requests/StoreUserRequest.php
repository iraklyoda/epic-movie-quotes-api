<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'username'       => ['required', 'unique:users'],
            'email'          => ['required', 'unique:users'],
            'password'       => ['required'],
        ];
    }
}