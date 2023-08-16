<?php
/**
 * @category  Ebolution
 * @package   ebolution/bigcommerce-app-adapter
 * @author    Carlos Cid <carlos.cid@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   MIT
 */

namespace Ebolution\BigcommerceAppAdapter\Infrastructure;

use Ebolution\ModuleManager\Infrastructure\ServicesProvider as ModuleManagerServiceProviders;

final class ServicesProvider extends ModuleManagerServiceProviders
{
    const BASE_DIR = __DIR__;

    protected $providers = [
        ServiceProviders\DependencyServicesProvider::class,
        ServiceProviders\RouteServicesProvider::class
    ];

    public function boot()
    {
        if (app()->runningInConsole()) {
            $this->registerMigrations();
        } else {
            $this->loadViewsFrom(__DIR__.'/../../resources/views', 'bigcommerce-app-adapter');
        }

        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'bigcommerce-app-adapter');
        $this->publishes([
            __DIR__.'/../../resources/lang' => $this->app->langPath('vendor/bigcommerce-app-adapter'),
        ], 'bigcommerce-app-adapter');

        $this->publishes([
            __DIR__.'/../../config/bigcommerce-app-adapter.php' => config_path('bigcommerce-app-adapter.php'),
        ], 'bigcommerce-app-adapter');
    }
}
