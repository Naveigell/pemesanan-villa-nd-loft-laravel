<?php

namespace App\Http\Requests\Admin;

use App\Foundations\BaseRequest;

class SuggestionRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "message" => "required|string|max:2000",
        ];
    }
}
