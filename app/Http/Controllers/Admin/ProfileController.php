<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordRequest;
use App\Http\Requests\Admin\ProfileRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function create()
    {
        return view('admin.pages.profile.form');
    }

    public function store(ProfileRequest $request)
    {
        auth()->user()->update($request->validated());

        return redirect(route('admin.profile.create'))->with('success', 'Biodata berhasil diubah');
    }

    public function password(PasswordRequest $request)
    {
        auth()->user()->update(["password" => $request->get('password')]);

        return redirect(route('admin.profile.create'))->with('success-password', 'Password berhasil diubah');
    }
}
