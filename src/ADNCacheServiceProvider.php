<?php

namespace Darkpony\ADNCache;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;

class ADNCacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router, Kernel $kernel)
    {
        //$router->aliasMiddleware('adncache', ADNCacheMiddleware::class);
        $kernel->pushMiddleware(ADNCacheMiddleware::class);

        $this->publishes([
            __DIR__ . '/../config/adncache.php' => config_path('adncache.php'),
        ], 'config');
    }
}
