<?php

namespace App\Http\Requests\Admin;

use App\Enums\RoomPriceTypeEnum;
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
        $priceRules = [];

        // Add validation rules for each price type
        foreach (RoomPriceTypeEnum::cases() as $case) {
            $priceRules['prices.' . $case->value] = 'required|min:0|integer|max:' . 9000_000_000_000;
        }

        return [
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:255',
            'color' => 'required|hex_color',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',

            ...$priceRules,
        ];
    }

    /**
     * Retrieves the attributes for the request.
     *
     * @return array The attributes for the request.
     */
    public function attributes()
    {
        $attributes = [];

        foreach (RoomPriceTypeEnum::cases() as $case) {
            $attributes['price.' . $case->value] = $case->value;
        }

        return $attributes;
    }
}
