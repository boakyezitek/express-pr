<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Property;
use App\Models\Staff;
use App\Models\Tenant;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Relation::enforceMorphMap([
            'staff' => Staff::class,
            'tenant' => Tenant::class,
            'client' => Client::class,
            'vendor' => Vendor::class,
            'property' => Property::class,
        ]);
    }
}
