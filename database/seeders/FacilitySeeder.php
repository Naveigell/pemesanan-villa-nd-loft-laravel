<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            'TV',
            'Wifi',
            'AC',
            'Dapur',
            'Pemanas',
            'Kolam Renang',
            'Gym',
            'Kulkas',
        ];

        foreach ($facilities as $facility) {
            Facility::create([
                'name' => $facility,
            ]);
        }
    }
}
