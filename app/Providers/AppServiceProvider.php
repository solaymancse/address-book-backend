<?php

namespace App\Providers;

use App\Interfaces\AddressBookRepositoryInterface;
use App\Interfaces\AuthRepositoryInterface;
use App\Repositories\AddressBookRepository;
use App\Repositories\AuthRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(AddressBookRepositoryInterface::class, AddressBookRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
