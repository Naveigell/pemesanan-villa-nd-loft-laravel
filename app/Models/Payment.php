<?php

namespace App\Models;

use App\Enums\PaymentTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Traits\CanSaveFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory, CanSaveFile;

    protected $fillable = ['booking_id', 'snap_token', 'payload', 'response', 'signature', 'status_code', 'payment_type', 'transaction_status'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    protected $casts = [
        'payment_type'       => PaymentTypeEnum::class,
        'transaction_status' => PaymentStatusEnum::class,
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
