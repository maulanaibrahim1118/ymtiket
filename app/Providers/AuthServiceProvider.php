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
            return $user->role === 'service desk';
        });

        // Menetapkan peran "Agent"
        Gate::define('isAgent', function ($user) {
            return $user->role === 'agent all' || $user->role === 'agent store' || $user->role === 'agent head office';
        });

        // Menetapkan peran "Client"
        Gate::define('isClient', function ($user) {
            return $user->role === 'client';
        });

        Gate::define('manage-ticket', function ($user) {
            return $user->role === 'service desk' || $user->role === 'client';
        });

        Gate::define('agent-info', function ($user) {
            return $user->role === 'service desk' || $user->role === 'agent all' || $user->role === 'agent store' || $user->role === 'agent head office';
        });

        Gate::define('isIT', function ($user) {
            return $user->location_id === 10;
        });

        Gate::define('isKorwil', function ($user) {
            return $user->position_id === 6;
        });
    }
}
