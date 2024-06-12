<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FacilityRequest;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facilities = Facility::paginate(10);

        return view('admin.pages.facility.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.facility.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FacilityRequest $request)
    {
        Facility::create($request->validated());

        return redirect(route('admin.facilities.index'))->with('success', 'Fasilitas berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility)
    {
        return view('admin.pages.facility.form', compact('facility'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FacilityRequest $request, Facility $facility)
    {
        $facility->update($request->validated());

        return redirect(route('admin.facilities.index'))->with('success', 'Fasilitas berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility)
    {
        $facility->delete();

        return redirect(route('admin.facilities.index'))->with('success', 'Fasilitas berhasil dihapus');
    }
}
