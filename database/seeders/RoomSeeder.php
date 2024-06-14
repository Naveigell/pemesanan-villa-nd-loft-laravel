<?php

namespace Database\Seeders;

use App\Models\Room;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            Room::create([
                'name' => $name,
                'code' => str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'price' => ($index + 1) * 100_000,
                'color' => $faker->hexColor,
            ]);
        }
    }
}
