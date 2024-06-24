<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomImageRequest;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Room $room)
    {
        $images = RoomImage::where('room_id', $room->id)->paginate();

        return view('admin.pages.room_image.index', compact('images', 'room'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Room $room)
    {
        return view('admin.pages.room_image.form', compact('room'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoomImageRequest $request, Room $room)
    {
        try {
            $image = new RoomImage();
            $image->saveFile('image', $request->file('image'), $image->fullPath());
            $image->room()->associate($room);
            $image->save();
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }

        return redirect(route('admin.rooms.rooms-images.index', $room))->with('success', 'Gambar berhasil di tambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomImage $roomImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room, RoomImage $roomImage)
    {
        return view('admin.pages.room_image.form', compact('room', 'roomImage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomImage $roomImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room, RoomImage $image)
    {
        $image->saveOriginalAttributes();
        $image->delete();
        $image->deleteImage();

        return redirect(route('admin.rooms.rooms-images.index', $room))->with('success', 'Gambar berhasil di hapus');
    }
}
