<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Mawar', 'Melati', 'Kamboja', 'Anggrek'];

        foreach ($names as $index => $name) {
            \App\Models\Room::create([
                'name' => $name,
                'code' => str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'price' => ($index + 1) * 100_000,
            ]);
        }
    }
}
