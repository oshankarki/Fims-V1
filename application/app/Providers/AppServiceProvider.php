<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ItemInfoRepository;
use App\Repositories\Interfaces\ItemInfoInterface;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Container\Container;
use Carbon\Carbon;
use Debugbar; // Ensure Debugbar is imported if used

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param UrlGenerator $url
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        // Disable debug bar in production mode if environment variable is set
        if (!config('app.debug')) {
            Debugbar::disable();
        }

        // Force SSL URLs if environment variable ENFORCE_SSL is true
        if (env('ENFORCE_SSL', false)) {
            $url->forceScheme('https');
        }

        // Use Bootstrap CSS for paginator
        Paginator::useBootstrap();

        // Bind Carbon instance to use Europe/Brussels timezone
        $this->app->bind(Carbon::class, function (Container $container) {
            return new Carbon('now', 'Europe/Brussels');
        });

        // Bind ItemInfoInterface to ItemInfoRepository
        $this->app->bind(ItemInfoInterface::class, ItemInfoRepository::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\Interface\ItemInfoInterface::class,
            \App\Repositories\ItemInfoRepository::class
        );
    }
}
