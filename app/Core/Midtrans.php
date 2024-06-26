<?php

namespace App\Core;

use Faker\Factory;
use Midtrans\Config;
use Midtrans\Snap;
use Ramsey\Uuid\Uuid;

class Midtrans
{
    /**
     * @var array
     */
    private array $transactionDetails;

    /**
     * @var array
     */
    private array $itemDetails;

    /**
     * @var array
     */
    private array $customerDetails;

    private $fake = false;

    public function __construct(array $transactionDetails, array $itemsDetails = [], array $customerDetails = [])
    {
        $this->transactionDetails = $transactionDetails;
        $this->itemDetails = $itemsDetails;
        $this->customerDetails = $customerDetails;

        $this->initConfig();
    }

    private function initConfig()
    {
        if (app()->isProduction() && !$this->fake) {
            Config::$serverKey = config('midtrans.server_production_key');
        } else {
            Config::$serverKey = config('midtrans.server_development_key');
        }

        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Get snap token from midtrans
     *
     * @throws \Exception
     */
    public function snapToken()
    {
        if ($this->fake) {
            return Uuid::uuid4()->toString();
        }

        return Snap::getSnapToken($this->payload());
    }

    /**
     * Get redirect url from midtrans
     *
     * @throws \Exception
     */
    public function redirectUrl()
    {
        if ($this->fake) {
            return 'https://midtrans.com';
        }

        return Snap::createTransaction($this->payload())->redirect_url;
    }

    public function payload()
    {
        return [
            'transaction_details' => $this->transactionDetails,
            'item_details' => $this->itemDetails,
            'customer_details' => $this->customerDetails
        ];
    }

    /**
     * @param array $transactionDetails
     *
     * return $this
     */
    public function setTransactionDetails(array $transactionDetails)
    {
        $this->transactionDetails = $transactionDetails;

        return $this;
    }

    /**
     * @param array $itemDetails
     *
     * return $this
     */
    public function setItemDetails(array $itemDetails)
    {
        $this->itemDetails = $itemDetails;

        return $this;
    }

    /**
     * @param array $customerDetails
     *
     * return $this
     */
    public function setCustomerDetails(array $customerDetails)
    {
        $this->customerDetails = $customerDetails;

        return $this;
    }

    protected function createFake()
    {
        $this->fake = true;

        return $this;
    }

    /**
     * Generate transaction data for a fake transaction.
     *
     * @param bool $success Whether the transaction is successful or not. Default is true.
     * @return array|null The transaction data as an associative array or null if not in fake mode.
     */
    public function transactionData(bool $success = true)
    {
        if ($this->fake) {
            // TODO: add failed response data
            $faker = Factory::create('id_ID');

            $orderId     = Uuid::uuid4()->toString();
            $statusCode  = 200;
            $grossAmount = rand(1, 9) * pow(10, rand(5, 7));

            return [
                "transaction_time"   => date('Y-m-d H:i:s'),
                "transaction_status" => "settlement",
                "transaction_id"     => Uuid::uuid4()->toString(),
                "store"              => "indomaret",
                "status_message"     => "midtrans payment notification",
                "status_code"        => $statusCode,
                "signature_key"      => hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_development_key')),
                "settlement_time"    => date('Y-m-d H:i:s'),
                "payment_type"       => "cstore",
                "payment_code"       => $faker->numerify('############'),
                "order_id"           => $orderId,
                "merchant_id"        => $faker->randomLetter . $faker->numerify('##########'),
                "gross_amount"       => $grossAmount,
                "expiry_time"        => date('Y-m-d H:i:s', strtotime('+1 day')),
                "currency"           => "IDR",
                "approval_code"      => $faker->numerify('##################'),
            ];
        }

        return null;
    }

    /**
     * Generates a fake transaction and customer details for testing purposes.
     *
     * @param array $itemDetails (optional) The details of the items.
     * @return self The instance of the class.
     */
    public static function fake($itemDetails = [])
    {
        $faker = Factory::create('id_ID');

        $transactionDetails = [
            'order_id' => $faker->uuid,
            'gross_amount' => $faker->numerify('##0000'),
        ];

        $customerDetails = [
            'first_name'    => $faker->firstName,
            'last_name'     => $faker->lastName,
            'email'         => $faker->email,
            'phone'         => $faker->numerify('+628############'),
            'billing_address'  => [
                'first_name'    => $faker->firstName,
                'last_name'     => $faker->lastName,
                'address'       => $faker->address,
                'city'          => $faker->city,
                'postal_code'   => $faker->postcode,
                'phone'         => $faker->numerify('+628############'),
                'country_code'  => $faker->countryCode,
            ],
            'shipping_address' => [
                'first_name'    => $faker->firstName,
                'last_name'     => $faker->lastName,
                'address'       => $faker->address,
                'city'          => $faker->city,
                'postal_code'   => $faker->postcode,
                'phone'         => $faker->numerify('+628############'),
                'country_code'  => $faker->countryCode,
            ]
        ];

        return (new self($transactionDetails, $customerDetails, $itemDetails))->createFake();
    }
}
