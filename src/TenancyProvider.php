<?php

namespace Sbine\Tenancy;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class TenancyProvider extends ServiceProvider implements DeferrableProvider
{
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Tenant::class, function () {
            return new Tenant(Auth::user(), function () {
                return ! Auth::check();
            });
        });
    }

    public function provides(): array
    {
        return [
            Tenant::class,
        ];
    }
}
