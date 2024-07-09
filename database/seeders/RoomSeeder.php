<?php

namespace Database\Seeders;

use App\Enums\RoomPriceTypeEnum;
use App\Models\Room;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('id_ID');
        $names = ['Mawar', 'Melati', 'Kamboja', 'Anggrek'];

        foreach ($names as $index => $name) {
            DB::beginTransaction();
            try {
                $room = Room::create([
                    'name' => $name,
                    'code' => str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                    'color' => $faker->hexColor,
                ]);

                // create room price with different types
                foreach (RoomPriceTypeEnum::cases() as $case) {
                    $room->prices()->create([
                        'type' => $case->value,
                        'price' => rand(1, 4) * $this->getBasePrice($case),
                    ]);
                }

                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();

                dd($exception->getMessage());
            }

        }
    }

    /**
     * Calculate the price based on the RoomPriceTypeEnum.
     *
     * @param RoomPriceTypeEnum $enum The type of room price
     * @return int The calculated price based on the enum type
     */
    private function getBasePrice(RoomPriceTypeEnum $enum)
    {
        return match ($enum) {
            RoomPriceTypeEnum::YEAR => 100_000,
            RoomPriceTypeEnum::MONTH => 10_000,
            RoomPriceTypeEnum::DAY => 1_000,
        };
    }
}
