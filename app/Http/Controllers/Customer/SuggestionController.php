<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\SuggestionRequest;
use App\Models\Suggestion;
use App\Models\SuggestionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuggestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suggestions = Suggestion::with('latestDetail.user.userable')
            ->withCount('details')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('customer.pages.suggestion.index', compact('suggestions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.pages.suggestion.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SuggestionRequest $request)
    {
        // create suggestion and it's detail
        $suggestion = DB::transaction(function () use ($request) {
            $suggestion = new Suggestion($request->validated());
            $suggestion->user()->associate(auth()->user());
            $suggestion->save();

            $detail = new SuggestionDetail($request->validated());
            $detail->user()->associate(auth()->user());
            $detail->suggestion()->associate($suggestion);
            $detail->save();

            return $suggestion;
        });

        return redirect(route('customer.suggestions.edit', $suggestion))->with('success', 'Berhasil mengirim pesan');
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
        abort_if($suggestion->user_id != auth()->id(), 404);

        $suggestion->load('details.user.userable');

        return view('customer.pages.suggestion.form', compact('suggestion'));
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

        return redirect(route('customer.suggestions.edit', $suggestion))->with('success', 'Berhasil mengirim pesan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Suggestion $suggestion)
    {
        //
    }
}
