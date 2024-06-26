<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;
use App\Utils\Midtrans;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class BookingSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::all();
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

                $payment = $this->payment($booking);

                // if it has payment, update the booking status
                if ($payment) {
                    $booking->update(['status' => BookingStatus::APPROVED->value]);
                } else {
                    // else change status to pending or cancelled because we don't have payment yet
                    $booking->update(['status' => Arr::random([BookingStatus::PENDING->value, BookingStatus::CANCELLED->value])]);
                }
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
     * @return Payment|null
     * @throws \Exception
     */
    private function payment(Booking $booking)
    {
        if (rand(0, 10) < 5) {
            $midtrans = Midtrans::fake();

            $response = $midtrans->transactionData();

            $payment = new Payment([
                'snap_token'         => $midtrans->snapToken(),
                'payload'            => json_encode($midtrans->payload()),
                'response'           => json_encode($response),
                'signature'          => $response['signature_key'],
                'status_code'        => $response['status_code'],
                'payment_type'       => $response['payment_type'],
                'transaction_status' => $response['transaction_status']
            ]);

            $payment->booking()->associate($booking);
            $payment->save();

            return $payment;
        }

        return null;
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
