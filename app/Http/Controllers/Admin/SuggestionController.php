<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SuggestionRequest;
use App\Models\Suggestion;
use App\Models\SuggestionDetail;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suggestions = Suggestion::withCount('details')->latest()->paginate(10);

        return view('admin.pages.suggestion.index', compact('suggestions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Suggestion $suggestion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Suggestion $suggestion)
    {
        $suggestion->load('details.user.userable');

        return view('admin.pages.suggestion.form', compact('suggestion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SuggestionRequest $request, Suggestion $suggestion)
    {
        $detail = new SuggestionDetail($request->validated());
        $detail->user()->associate(auth()->user());
        $detail->suggestion()->associate($suggestion);
        $detail->save();

        return redirect(route('admin.suggestions.edit', $suggestion))->with('success', 'Berhasil mengirim pesan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Suggestion $suggestion)
    {
        //
    }
}
