<?php

namespace App\Http\Resources\Api\V1\Admin;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookingCalendarCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function (Booking $booking) {
            return [
                "id" => $booking->id,
                "code" => $booking->code,
                "customer_name" => $booking->customer_name,
                "customer_phone" => $booking->customer_phone,
                "customer_email" => $booking->customer_email,
                "customer_address" => $booking->customer_address,
                "booking_days" => $booking->from_date->diffInDays($booking->until_date) + 1, // should add 1 because it's not include until date
                "room" => [
                    "id" => $booking->room->id,
                    "name" => $booking->room->name,
                    "color" => $booking->room->color,
                    "price" => $booking->room->price_integer,
                ],
                "from_date" => $booking->from_date->format('Y-m-d'),
                "until_date" => $booking->until_date->format('Y-m-d'),
                "status" => $booking->status->value,
            ];
        })->toArray();
    }
}
