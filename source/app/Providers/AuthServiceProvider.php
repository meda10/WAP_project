<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('reserve-title', function($user) {
            return $user->hasRole(['manager', 'customer', 'owner', 'director', 'employee']) && $user->isConfirmed();
        });

//        Gate::before(function ($user, $ability) {
//            return $user->hasRole(['director']) ? true : null;
//        });
    }
}
