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
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Menetapkan peran "Service Desk"
        Gate::define('isServiceDesk', function ($user) {
            return $user->role_id === 1;
        });

        // Menetapkan peran "Agent"
        Gate::define('isAgent', function ($user) {
            return $user->role_id === 2;
        });

        // Menetapkan peran "Client"
        Gate::define('isClient', function ($user) {
            return $user->role_id === 3;
        });

        Gate::define('manage-ticket', function ($user) {
            return $user->role_id === 1 || $user->role_id === 3;
        });

        Gate::define('agent-info', function ($user) {
            return $user->role_id === 1 || $user->role_id === 2;
        });

        Gate::define('isIT', function ($user) {
            return $user->location_id === 10;
        });

        Gate::define('isKorwil', function ($user) {
            return $user->position_id === 6;
        });

        Gate::define('isActor', function ($user) {
            return $user->position_id === 1 || $user->position_id === 2 ||
                $user->position_id === 4 || $user->position_id === 9 || 
                $user->position_id === 5 || $user->position_id === 6;
        });
    }
}