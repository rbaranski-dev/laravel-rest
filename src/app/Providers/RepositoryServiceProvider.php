<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Interfaces\BuisnessRepositoryInterface::class,
            \App\Repositories\BuisnessRepository::class
        );
        $this->app->bind(
            \App\Interfaces\EmployeeRepositoryInterface::class,
            \App\Repositories\EmployeeRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
