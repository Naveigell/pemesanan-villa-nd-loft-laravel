<?php

namespace Database\Seeders;

use App\Models\Suggestion;
use App\Models\SuggestionDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuggestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = \App\Models\User::whereHasMorph('userable', [\App\Models\Customer::class])->get();
        $admins    = \App\Models\User::whereHasMorph('userable', [\App\Models\Admin::class])->get();

        $faker = \Faker\Factory::create('id_ID');

        foreach ($customers as $customer) {
            foreach (range(1, rand(5, 8)) as $_) {
                DB::transaction(function () use ($admins, $customers, $customer, $faker) {
                    $suggestions = new Suggestion();
                    $suggestions->user()->associate($customer);
                    $suggestions->save();

                    // looping customer and admin to fill suggestions
                    foreach ([$customer, $admins->random()] as $user) {
                        $detail = new SuggestionDetail(["message" => $faker->sentence(10, 60)]);
                        $detail->user()->associate($user);
                        $detail->suggestion()->associate($suggestions);
                        $detail->save();
                    }
                });
            }
        }
    }
}
