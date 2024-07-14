<?php

namespace Database\Seeders;

use App\Enums\BookingStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\RoomPriceTypeEnum;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;
use App\Traits\CanFormatDateTimeByType;
use App\Utils\Midtrans;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class BookingSeeder extends Seeder
{
    use CanFormatDateTimeByType;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::with('prices')->get();
        $users = User::with('userable')->whereHasMorph('userable', [Customer::class])->get();
        $faker = Factory::create('id_ID');

        $counter = 0;

        while ($counter < 20) {
            $room = $rooms->random();

            $fromDate  = now()->addDays(rand(5, 80));
            $untilDate = $fromDate->clone()->addDays(rand(1, 2));

            // check if the date $fromDate and $untilDate is booked by another person
            $isRoomBooked = Booking::where('room_id', $room->id)
                ->where(function ($query) use ($fromDate, $untilDate) {

                    // check if the $fromDate and $until date is inside a range of another booking
                    // for example: $fromDate = 2024-06-27, and
                    //              $untilDate = 2024-06-28.
                    //
                    // Booking data from_date is '2024-06-25' and until_date is '2024-06-30', so we need to check if
                    // $fromDate is inside a range of '2024-06-25' and '2024-06-30', and then we check if
                    // $untilDate is inside a range of '2024-06-25' and '2024-06-30'
                    $query->where(fn ($query) => $query->whereBookedAt($fromDate->format('Y-m-d')))
                        ->orWhere(fn ($query) => $query->whereBookedAt($untilDate->format('Y-m-d')));
                })
                ->orWhere(function ($query) use ($fromDate, $untilDate) {

                    // check if from_date and until_date is inside a range of given date, for example: if given date is
                    // $from = '2024-06-25' and $until = '2024-06-30', so we need to check if from_date is in range of
                    // '2024-06-25' and '2024-06-30'
                    $query->whereDateInsideRange(
                        $fromDate->format('Y-m-d'),
                        $untilDate->format('Y-m-d')
                    );
                })->exists();

            // if the room has been booked by another person, create another date
            if ($isRoomBooked) {
                continue;
            }

            try {
                $customer = $this->customer($faker, $users->random());

                $booking = new Booking([
                    "code" => $faker->uuid,
                    "customer_name" => $customer->name,
                    "customer_email" => $customer->email,
                    "customer_phone" => $customer->phone,
                    "customer_address" => $customer->address,
                    "from_date" => $fromDate,
                    "until_date" => $untilDate,
                ]);

                if ($customer->user_id) {
                    $booking->user()->associate($customer->user_id);
                }

                $booking->room()->associate($room);
                $booking->save();

                // if booking is approved, create payment
                if (rand(1, 10) < 5) {
                    $booking->update(['status' => BookingStatusEnum::APPROVED->value]);
                } else {
                    // else change status to pending or cancelled because we don't have payment yet
                    $booking->update(['status' => Arr::random([BookingStatusEnum::PENDING->value, BookingStatusEnum::CANCELLED->value])]);
                }

                // sync price id from room_price into booking_room_price table
                // get the type of day because if we want to add another type such like month or year, should do more effort
                $priceId = $room->prices->where('type', RoomPriceTypeEnum::DAY)->first()->id;

                $booking->roomPrice()->sync($priceId);

                $this->payment($booking, $this->getPaymentStatus($booking->status->value));
            } catch (\Exception $exception) {
                dd($exception->getMessage());
            }

            $counter++;
        }
    }

    /**
     * Create payment instance
     *
     * @param Booking $booking
     * @param null $transactionStatus
     * @return Payment|null
     * @throws \Exception
     */
    private function payment(Booking $booking, $transactionStatus = null)
    {
        $items = $this->itemDetails($booking);

        $midtrans = Midtrans::fake([$items]);

        $response = $midtrans->transactionData();

        $payment = new Payment([
            'snap_token'         => $midtrans->snapToken(),
            'payload'            => json_encode($midtrans->payload()),
            'response'           => json_encode($response),
            'signature'          => $response['signature_key'],
            'status_code'        => $response['status_code'],
            'payment_type'       => $response['payment_type'],
            'transaction_status' => $transactionStatus ?? $response['transaction_status'],
            'paid_at'            => $response['transaction_time'],
        ]);

        $payment->booking()->associate($booking);
        $payment->save();

        return $payment;
    }

    /**
     * Get the corresponding payment status based on the booking status.
     *
     * @param string $bookingStatus The booking status.
     * @return string The corresponding payment status.
     */
    private function getPaymentStatus($bookingStatus)
    {
        // match booking status with payment status, if booking approved, set payment status to settlement, etc.
        $statuses = [
            BookingStatusEnum::APPROVED->value  => PaymentStatusEnum::SETTLEMENT->value,
            BookingStatusEnum::PENDING->value   => PaymentStatusEnum::PENDING->value,
            BookingStatusEnum::CANCELLED->value => PaymentStatusEnum::CANCEL->value
        ];

        if (array_key_exists($bookingStatus, $statuses)) {
            return $statuses[$bookingStatus];
        }

        return PaymentStatusEnum::PENDING->value;
    }

    /**
     * Retrieves the item details for a given booking.
     * @see \App\Http\Controllers\Customer\ReservationController@store for more details
     *
     * @param Booking $booking The booking object.
     * @return array The item details including id, name, code, price, diff, quantity, and room_price_type.
     */
    private function itemDetails(Booking $booking)
    {
        $booking->loadMissing('roomPrice', 'room');

        $room = $booking->room;

        $type = $booking->roomPrice->first()->type;

        $diff = $this->diffOfDate($type, $booking->from_date, $booking->until_date);

        // get total price by multiplying room price and diff between today and until
        // just take formatted price because the real price attributes is float (can't input float)
        $totalPrice = $booking->roomPrice->first()->price_integer * $diff;

        $items = $room->only('id', 'name', 'code'); // remember every item should have id
        $items['price']           = $totalPrice;
        $items['diff']            = $diff;
        $items['quantity']        = 1;
        $items['room_price_type'] = $type->value;

        return $items;
    }

    /**
     * Create customer based on user or faker
     *
     * @param \Faker\Generator $faker
     * @param User $user
     * @return \stdClass
     */
    private function customer($faker, User $user)
    {
        $customer = new \stdClass();

        if (rand(0, 10) < 5) {
            /**
             * @var Customer $userable
             */
            $userable = $user->userable;

            $customer->name = $user->name;
            $customer->email = $user->email;
            $customer->phone = $userable->phone;
            $customer->address = $userable->address;
            $customer->user_id = $user->id;
        } else {
            $customer->name = $faker->name;
            $customer->email = $faker->email;
            $customer->phone = $faker->numerify('+62##########');
            $customer->address = $faker->address;
            $customer->user_id = null;
        }

        return $customer;
    }
}
