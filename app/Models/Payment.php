<?php

namespace App\Models;

use App\Enums\PaymentTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\RoomPriceTypeEnum;
use App\Traits\CanSaveFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory, CanSaveFile;

    protected $fillable = ['booking_id', 'snap_token', 'payload', 'response', 'signature', 'status_code', 'payment_type', 'transaction_status', 'paid_at'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    protected $casts = [
        'payment_type'       => PaymentTypeEnum::class,
        'transaction_status' => PaymentStatusEnum::class,
        'paid_at'            => 'date',
    ];

    /**
     * Get the response array attribute.
     *
     * @return array Returns the decoded JSON response if it exists, otherwise an empty array.
     */
    public function getResponseArrayAttribute()
    {
        if ($this->response) {
            return json_decode($this->response, true);
        }

        return [];
    }

    /**
     * Retrieves the payload array attribute.
     *
     * If the payload attribute is not empty, it decodes the JSON string
     * into an associative array and returns it. Otherwise, it returns an
     * empty array.
     *
     * @return array The decoded payload array, or an empty array if the payload
     */
    public function getPayloadArrayAttribute()
    {
        if ($this->payload) {
            return json_decode($this->payload, true);
        }

        return [];
    }

    /**
     * Retrieves the payment type detail based on the response array and payment type.
     *
     * @return string|null The payment type detail or null if not found
     */
    public function paymentTypeDetail()
    {
        $response = $this->response_array;

        if (count($response) == 0) {
            return null;
        }

        $paymentType = PaymentTypeEnum::tryFrom($response['payment_type']);

        if (!$paymentType) {
            return null;
        }

        // every payment type has their own different response, so we need to check that response in every case
        // such like credit card has another response and bank transfer has another response
        if ($paymentType->isCreditCard()) {
            return $paymentType->label() . ' (' . $response['bank'] . ')';
        }

        if ($paymentType->isQris()) {
            return $paymentType->label() . ' (' . $response['acquirer'] . ')';
        }

        if ($paymentType->isBankTransfer()) {
            if (array_key_exists('permata_va_number', $response)) {
                return $paymentType->label() . ' (Permata)';
            }

            if (array_key_exists('va_numbers', $response)) {
                $item = $response['va_numbers'][0];

                return $paymentType->label() . ' (' . strtoupper($item['bank']) . ')';
            }
        }

        if ($paymentType->isStore()) {
            return $paymentType->label() . ' (' . $response['store'] . ')';
        }

        return $paymentType->label();
    }

    /**
     * Get the gross amount attribute from the response array.
     *
     * @return int The gross amount value, or 0 if it does not exist.
     */
    public function getGrossAmountAttribute()
    {
        $response = $this->response_array;

        if (array_key_exists('gross_amount', $response)) {
            return $response['gross_amount'];
        }

        return 0;
    }

    /**
     * Get the room type price attribute from the payload array.
     *
     * This function retrieves the room type price from the payload array. It assumes that the payload array contains
     * an 'item_details' key, and that the first item in the details array has a 'room_price_type' key. If the
     * payload array or the details array is empty, or if the 'room_price_type' key is not found, it returns null.
     *
     * @return RoomPriceTypeEnum|null The room type price, or null if it cannot be found.
     */
    public function getRoomTypePriceAttribute()
    {
        $payload = $this->payload_array;

        /**
         * always get the first items in payload, payload item details never be empty, because we add it manually in
         * the controller
         * @see \App\Http\Controllers\Customer\ReservationController@store
         **/
        $items = $payload['item_details'];

        return RoomPriceTypeEnum::tryFrom($items[0]['room_price_type']);
    }

    /**
     * Retrieves the item duration attribute from the payload array.
     *
     * This function retrieves the item duration from the payload array. It assumes that the payload array contains
     * an 'item_details' key, and that the first item in the details array has a 'diff' key. If the
     * payload array or the details array is empty, or if the 'diff' key is not found, it returns null.
     *
     * @return int|null The item duration value, or null if it cannot be found.
     */
    public function getItemDurationAttribute()
    {
        $payload = $this->payload_array;

        return $payload['item_details'][0]['diff'];
    }

    /**
     * Get the payment URL attribute based on the environment.
     *
     * @return string
     */
    public function getPaymentUrlAttribute()
    {
        if (app()->isProduction()) {
            return Str::replace(':token', $this->snap_token, config('midtrans.production_redirect_url'));
        }

        return Str::replace(':token', $this->snap_token, config('midtrans.development_redirect_url'));
    }

    public function getPaymentProofImageUrlAttribute()
    {
        if (file_exists(storage_path("app/{$this->fullPath()}/{$this->payment_proof_image}"))) {
            return asset("storage/payments/{$this->payment_proof_image}");
        }

        return "https://placehold.co/600x400";
    }

    /**
     * Return the full path where the file will be saved
     *
     * @return string
     */
    public function fullPath()
    {
        return $this->options['root_folder'] . '/payments';
    }
}
