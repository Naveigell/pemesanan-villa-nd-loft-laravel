<?php

namespace App\Http\Requests\Customer;

use App\Enums\RoomPriceTypeEnum;
use App\Foundations\BaseRequest;
use App\Models\Booking;
use App\Traits\CanFormatDateTimeByType;

class ReservationRequest extends BaseRequest
{
    use CanFormatDateTimeByType;

    /**
     * @var RoomPriceTypeEnum
     */
    private $type;

    public function authorize()
    {
        $from  = $this->query('from');
        $until = $this->query('until');
        $type  = $this->type;

        // should have from and until
        if (!$from || !$until || !$type) {
            return false;
        }

        // format date time
        $date = $this->formatDateTime($type, $from, $until);

        $from = $date->from;
        $until = $date->until;

        // don't give from is greater than until
        if ($from->gte($until)) {
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
        $type = $this->type;

        $rules = [
            "customer_name"    => "required|string|min:5|max:255",
            "customer_email"   => "required|email|string|min:5|max:255",
            "customer_phone"   => "required|string|min:5|max:255",
            "customer_address" => "required|string|min:5|max:255",
            "from_date"        => "required|date",
            "until_date"       => "required|date|after:from_date",
            "notes"            => "nullable|string|min:5|max:1000",
        ];

        // if the user is customer, we don't need validation for customer fields such like name, email, phone or address
        // because we will get it by it's user data
        if (auth()->check() && auth()->user()->isCustomer()) {
            $rules = [
                "from_date"        => "required|date",
                "until_date"       => "required|date|after:from_date",
                "notes"            => "nullable|string|min:5|max:1000",
            ];
        }

        $date = now()->toDateString();

        // if type is year, take the year, if type is month, take the year and month, to format it in carbonFormatted method,
        if ($type->isYear()) {
            $date = now()->year;
        } elseif ($type->isMonth()) {
            $date = now()->format('Y-m');
        }

        // if type is year, just take the year, if type is month, take the year and month
        $from = $this->getCarbonFormatted($type, $date);

        // if the type is year or month, we create from date by formatted carbon, it should be 'Y-m-d' or for example '2024-03-01' for month
        // or '2024-01-01' for year
        if ($type->isYear() || $type->isMonth()) {
            $rules["from_date"]  = "required|date|date_format:Y-m-d|after_or_equal:" . $from->format('Y-m-d');
            $rules["until_date"] = "required|date|date_format:Y-m-d|after:from_date";
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $type = RoomPriceTypeEnum::tryFrom(request('type'));
        $this->type = $type;

        // abort if the type is invalid
        abort_if(!$type, 404);

        // format date time to add to validation
        $date = $this->formatDateTime($type, request('from'), request('until'));

        $from_date  = $date->from->format('Y-m-d');
        $until_date = $date->until->format('Y-m-d');

        $this->request->add(compact('from_date', 'until_date'));
    }
}
