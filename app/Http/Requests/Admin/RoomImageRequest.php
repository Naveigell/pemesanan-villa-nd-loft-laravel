<?php

namespace App\Http\Requests\Admin;

use App\Foundations\BaseRequest;

class RoomImageRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'required|image|mimes:jpg,png,jpeg|min:1|max:' . (1024 * 15), // 15 MB
        ];
    }
}
