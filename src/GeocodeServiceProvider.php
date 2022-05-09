<?php

namespace Markuskooche\Geocode;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application as LaravelApplication;
use Markuskooche\Geocode\Facades\Geocode as GeocodeFacade;

/**
 * The service provider to register and boot the Geocode package.
 *
 * @author Markus Koch
 * @license MIT
 * @package Markuskooche\Geocode
 */
class GeocodeServiceProvider extends ServiceProvider
{
/**
     * Bootstrap the geocode configuration.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot() : void
    {
        $source = dirname(__DIR__).'/../../config/geocode.php';

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('geocode.php')]);
        }

        $kernel = $this->app->make(Kernel::class);

        $kernel->bootstrap();


        //$this->mergeConfigFrom($source, 'geocode');
    }

    /**
     * Register the geocode service provider.
     *
     * @return void
     */
    public function register() : void
    {
        $this->app->singleton('geocode', fn() => new Geocode());

        if ($this->app instanceof LaravelApplication) {
            $this->app->booting(function () {
                $loader = AliasLoader::getInstance();
                $loader->alias('Geocode', GeocodeFacade::class);
            });
        }
    }
}