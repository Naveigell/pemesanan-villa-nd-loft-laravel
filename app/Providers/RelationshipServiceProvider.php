<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class RelationshipServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            "customer" => Customer::class,
            "admin" => Admin::class,
        ]);
    }
}
