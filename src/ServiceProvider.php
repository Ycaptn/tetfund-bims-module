<?php
namespace TETFund\BIMSOnboarding;

use TETFund\BIMSOnboarding\Facades;
use TETFund\BIMSOnboarding\Providers\BIMSOnboardingEventServiceProvider;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Engines\EngineResolver;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
    * Publishes configuration file.
    *
    * @return  void
    */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/tetfund-bims.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tetfund-bims-module');

        // Publish view files
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/tetfund-bims-module'),
        ], 'views');

        // Publish assets
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('tetfund-bims-module'),
        ], 'assets');

        // Publish view components
        $this->publishes([
            __DIR__.'/../src/View/Components/' => app_path('View/Components'),
            __DIR__.'/../resources/views/components/' => resource_path('views/components'),
        ], 'view-components');

        $this->publishes([
            __DIR__ . '/../database/seeders/BIMSOnboardingSeeder.php' => database_path('seeders/BIMSOnboardingSeeder.php'),
        ], 'seeders');

        
        Blade::componentNamespace('TETFund\\BIMSOnboarding\\View\\Components', 'tetfund-bims-module');
    }

    /**
    * Make config publishing optional by merging the config from the package.
    *
    * @return  void
    */
    public function register()
    {
        $configPath = __DIR__ . '/../config/tetfund-bims.php';
        $this->mergeConfigFrom($configPath, 'tetfund-bims');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->app->bind('BIMSOnboarding', function($app) {
            return new BIMSOnboarding();
        });

        $this->app->register(BIMSOnboardingEventServiceProvider::class);

    }

        /**
     * Get the active router.
     *
     * @return Router
     */
    protected function getRouter()
    {
        return $this->app['router'];
    }

    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('tetfund-bims.php');
    }

    /**
     * Publish the config file
     *
     * @param  string $configPath
     */
    protected function publishConfig($configPath)
    {
        $this->publishes([$configPath => config_path('tetfund-bims.php')], 'config');
    }

    /**
     * Register a Middleware
     *
     * @param  string $middleware
     */
    protected function registerMiddleware($middleware)
    {
        $kernel = $this->app[Kernel::class];
        $kernel->pushMiddleware($middleware);
    }
}