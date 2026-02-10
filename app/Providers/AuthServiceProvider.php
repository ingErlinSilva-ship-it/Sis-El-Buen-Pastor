<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
{
    // Tu lógica de Gate::before está excelente para el Admin
    Gate::before(function ($user, $ability) {
        if ($user->rol_id == 1) {
            return true;
        }
    });

    Gate::define('administrador', function ($user) {
        return $user->rol_id == 1;
    });

    Gate::define('doctor', function ($user) {
        return $user->rol_id == 2;
    });

    Gate::define('paciente', function ($user) {
        return $user->rol_id == 3;
    });

    // AGREGA ESTO: Es la llave que abre el grupo compartido en web.php
    Gate::define('doctor-o-administrador', function ($user) {
        return $user->rol_id == 1 || $user->rol_id == 2;
    });
    }
}