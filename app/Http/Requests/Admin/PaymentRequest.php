<?php

namespace App\Http\Requests\Admin;

use App\Enums\PaymentStatusEnum;
use App\Foundations\BaseRequest;

class PaymentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $statuses = collect(PaymentStatusEnum::cases())->map(fn ($status) => $status->value)->join(',');

        return [
            "payment_status" => "required|in:{$statuses}",
        ];
    }
}
