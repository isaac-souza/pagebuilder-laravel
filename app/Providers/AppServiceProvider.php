<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Repositories\LandingPage\LandingPageRepositoryInterface;
use App\Models\Repositories\LandingPage\LandingPageRepository;
use App\Models\Repositories\LandingPage\Decorators\LandingPageRepositoryCacheDecorator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LandingPageRepositoryInterface::class, function() {
            return new LandingPageRepositoryCacheDecorator(new LandingPageRepository);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
