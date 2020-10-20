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
            __DIR__.'/../config/localization.php' => config_path('localization.php'),
            __DIR__.'/../database/migrations/2020_08_10_000000_create_translations_table.php' => base_path('database/migrations/2020_08_10_000000_create_translations_table.php')
        ], 'translatable');

        // MIDDLEWARES
        $router->aliasMiddleware('localization', \Nh\Translatable\Http\Middleware\Localization::class);

        // ROUTES
        $this->loadRoutesFrom(__DIR__ . '/../routes/translatable.php');
    }
}
