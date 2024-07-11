<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request)
    {
        DB::transaction(function () use ($request) {
            $customer = Customer::create($request->validated());

            $user = new User($request->validated());
            $user->generateEmailVerifiedAt();
            $user->userable()->associate($customer);
            $user->save();
        });

        return redirect(route('login.index'))->with('success', 'Berhasil membuat akun, silakan login.');
    }
}
