<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConnectRoomAndFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::all();
        $facilities = Facility::all();

        foreach ($rooms as $room) {
            $room->facilities()->sync($facilities->random(3)->pluck('id')->toArray());
        }
    }
}
