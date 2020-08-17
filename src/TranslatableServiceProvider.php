<?php
namespace Nh\Translatable;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class TranslatableServiceProvider extends ServiceProvider
{

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {

        // VENDORS
        $this->publishes([
            __DIR__.'/../config/localization.php' => config_path('localization.php')
        ], 'translatable');

        // MIDDLEWARES
        $router->aliasMiddleware('loacalization', \Nh\Translatable\Http\Middleware\Localization::class);

        // ROUTES
        $this->loadRoutesFrom(__DIR__ . '/../routes/translatable.php');
    }
}
