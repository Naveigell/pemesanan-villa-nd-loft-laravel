<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "email"    => "required|email|exists:users|string|min:5|max:255",
            "password" => "required|string|min:5|max:255",
        ];
    }
}
