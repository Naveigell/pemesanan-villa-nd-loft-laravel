<?php

namespace App\Http\Requests\Admin;

use App\Foundations\BaseRequest;

class RoomRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:255',
            'price' => 'required|min:0|integer|max:' . 100_000_000,
            'color' => 'required|hex_color',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ];
    }
}
