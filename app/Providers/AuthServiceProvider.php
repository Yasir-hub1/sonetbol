<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\ServiceRequest;
use App\Policies\ServiceRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        ServiceRequest::class => ServiceRequestPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }

    /**
     * Register any authentication / authorization services.
     */

}
