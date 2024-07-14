<?php

namespace App\Http\Requests\Customer;

use App\Foundations\BaseRequest;
use App\Rules\OldPassword;

class PasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "old_password" => ["required", "string", "max:100", new OldPassword($this->old_password)],
            "password" => "required|string|max:100|same:retype_password",
            "retype_password" => "required|string|max:100|same:password",
        ];
    }
}
