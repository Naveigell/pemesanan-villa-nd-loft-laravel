<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('id_ID');

        DB::transaction(function () {
            $admin = Admin::create();

            $user = new User([
                "name" => "Admin",
                "email" => "admin@gmail.com",
                "password" => 123456,
                "email_verified_at" => now()->toDateTimeString(),
            ]);
            $user->userable()->associate($admin)->save();
        });

        foreach (range(1, 4) as $i) {
            DB::transaction(function () use ($faker, $i) {
                // specify only 1 customer to be had static data, for easy testing
                $name  = $i == 1 ? "Customer" : $faker->name;
                $email = $i == 1 ? "customer@gmail.com" : $faker->unique()->email;

                $this->createCustomer([
                    "address" => $faker->address,
                    "phone" => $faker->unique()->numerify('+628###########'),
                ], [
                    "name" => $name,
                    "email" => $email,
                    "password" => 123456,
                    "email_verified_at" => now()->toDateTimeString(),
                ]);
            });
        }
    }

    /**
     * Create a new customer and associate with a user
     *
     * @param array $customerData
     * @param array $userData
     * @return void
     */
    private function createCustomer($customerData = [], $userData = [])
    {
        // Create a new customer
        $customer = Customer::create($customerData);

        // Create a new user
        $user = new User($userData);

        // Associate the user with the customer and save
        $user->userable()->associate($customer)->save();
    }
}
