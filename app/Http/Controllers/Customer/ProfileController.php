<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\PasswordRequest;
use App\Http\Requests\Customer\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function create()
    {
        $user = auth()->user()->load('userable');

        return view('customer.pages.profile.form', compact('user'));
    }

    public function store(ProfileRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = auth()->user()->load('userable');

            $user->update($request->validated());
            $user->userable->update($request->validated());
        });

        return redirect(route('customer.profile.create'))->with('success', 'Profil berhasil di ubah');
    }

    public function password(PasswordRequest $request)
    {
        auth()->user()->update(["password" => $request->get('password')]);

        return redirect(route('customer.profile.create'))->with('success-password', 'Password berhasil diubah');
    }
}
