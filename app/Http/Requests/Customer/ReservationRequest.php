<?php

namespace App\Http\Requests\Customer;

use App\Foundations\BaseRequest;
use App\Models\Booking;

class ReservationRequest extends BaseRequest
{
    public function authorize()
    {
        $from  = $this->query('from');
        $until = $this->query('until');

        if (!$from || !$until) {
            return false;
        }

        /**
         * @var \App\Models\Room $room
         */
        $room = $this->route('room');

        // only accept when the date is not inside any another range in this room
        return Booking::whereDateInsideRange($from, $until)->where('room_id', $room->id)->doesntExist();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "customer_name"    => "required|string|min:5|max:255",
            "customer_email"   => "required|email|string|min:5|max:255",
            "customer_phone"   => "required|string|min:5|max:255",
            "customer_address" => "required|string|min:5|max:255",
            "from_date"        => "required|date",
            "until_date"       => "required|date|after:from_date",
            "notes"            => "nullable|string|min:5|max:1000",
        ];
    }
}
