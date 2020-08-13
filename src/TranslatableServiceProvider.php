<?php
namespace Nh\Translatable;

use Illuminate\Support\ServiceProvider;

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
    public function boot()
    {

        // VENDORS
        $this->publishes([
            __DIR__.'/../config/translatable.php' => config_path('translatable.php')
        ], 'translatable');

        // MIDDLEWARES
        $router->aliasMiddleware('loacalization', \Nh\Translatable\Http\Middleware\Localization::class);

        // ROUTES
        $this->loadRoutesFrom(__DIR__ . '/../routes/translatable.php');
    }
}
