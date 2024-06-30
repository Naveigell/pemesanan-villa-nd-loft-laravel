<?php

namespace App\Core;

use Exception;
use Faker\Factory;
use Illuminate\Support\Str;
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

    /**
     * A snap token that get from midtrans
     *
     * @var string
     */
    private $snapToken;

    /**
     * A signature that get from midtrans
     *
     * @var string
     */
    private $signature;

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
        // if it's fake mode, use fake token
        if ($this->fake) {
            $this->snapToken = Uuid::uuid4()->toString();
        }

        if (!$this->snapToken) {
            // if it's not fake mode, use real token
            try {
                $this->snapToken = Snap::getSnapToken($this->payload());
            } catch (Exception $exception) {
                dd($exception->getMessage(), $exception->getFile(), $this->payload());
            }
        }

        return $this->snapToken;
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

        $token = $this->snapToken();

        // replace token in redirect url
        if (app()->isProduction()) {
            return Str::replace(':token', $token, config('midtrans.production_redirect_url'));
        }

        return Str::replace(':token', $token, config('midtrans.development_redirect_url'));
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
                "signature_key"      => $this->constructSignatureKey($orderId, $statusCode, $grossAmount),
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
     * Constructs the signature key for a transaction.
     *
     * @param string $orderId The ID of the order.
     * @param int $statusCode The status code of the transaction.
     * @param float $grossAmount The gross amount of the transaction.
     * @return string The constructed signature key.
     */
    public function constructSignatureKey($orderId, $statusCode, $grossAmount, $key = null)
    {
        // if midtrans is fake mode, use development key
        if ($this->fake) {
            $key = config('midtrans.server_development_key');
        }

        // if key is not set, use production key or development key
        if (!$key) {
            $key = app()->isProduction() ? config('midtrans.server_production_key') : config('midtrans.server_development_key');
        }

        if (!$this->signature) {
            // the formula is got from midtrans documentation
            $this->signature = hash('sha512', $orderId . $statusCode . $grossAmount . $key);
        }

        return $this->signature;
    }

    /**
     * Creates a new instance of the class with an empty array passed as a parameter.
     * Used for testing method such as constructSignatureKey() method.
     *
     * @return self A new instance of the class.
     */
    public static function test()
    {
        return new self([]);
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

    /**
     * Set the snap token for the object.
     *
     * @param string $snapToken The snap token to set
     */
    public function setSnapToken(string $snapToken)
    {
        $this->snapToken = $snapToken;
    }
}
