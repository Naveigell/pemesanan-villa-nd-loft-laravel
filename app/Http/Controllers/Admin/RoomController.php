<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomRequest;
use App\Models\Facility;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::with('facilities', 'prices')->paginate(10);

        return view('admin.pages.room.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $facilities = Facility::all();
        $facilities = $facilities->chunk($facilities->count() / 2); // split facilities to 2 columns

        return view('admin.pages.room.form', compact('facilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoomRequest $request)
    {
        DB::transaction(function () use ($request) {
            $room = Room::create($request->validated());

            // create every price in room
            foreach ($request->prices as $type => $price) {
                $room->prices()->create([
                    'price' => $price,
                    'type' => $type,
                ]);
            }
        });

        return redirect(route('admin.rooms.index'))->with('success', 'Ruangan berhasil di tambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $facilities = Facility::all();
        $facilities = $facilities->chunk($facilities->count() / 2); // split facilities to 2 columns

        $room->load('facilities', 'prices');

        return view('admin.pages.room.form', compact('room', 'facilities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoomRequest $request, Room $room)
    {
        DB::beginTransaction();

        try {
            $room->update($request->validated());
            $room->facilities()->sync($request->facilities);

            // update every price in room
            foreach ($request->prices as $type => $price) {
                $room->prices()->where('type', $type)->update(['price' => $price]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return redirect(route('admin.rooms.index'))->with('success', 'Ruangan berhasil di ubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect(route('admin.rooms.index'))->with('success', 'Ruangan berhasil di hapus');
    }
}
