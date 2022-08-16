<?php

namespace Markuskooche\Geocode;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application as LaravelApplication;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Markuskooche\Geocode\Drivers\Driver;
use Markuskooche\Geocode\Drivers\GoogleMaps;
use Markuskooche\Geocode\Drivers\OpenStreetMap;
use Markuskooche\Geocode\Facades\Geocode as GeocodeFacade;

/**
 * The service provider to register and boot the Geocode package.
 *
 * @author Markus Koch
 * @license MIT
 */
class GeocodeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the geocode configuration.
     *
     * @return void
     *
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        if ($this->app instanceof LaravelApplication) {
            $kernel = $this->app->make(Kernel::class);
            $kernel->bootstrap();

            if ($this->app->runningInConsole()) {
                $source = __DIR__.'/../config/geocode.php';
                $this->publishes([$source => config_path('geocode.php')], 'config');
            }
        }
        // @phpstan-ignore-next-line
        elseif ($this->app instanceof LumenApplication) {
            $this->app->boot();
        }
    }

    /**
     * Register the geocode service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('geocode', fn () => new Geocode($this->configureDriver()));

        if ($this->app instanceof LaravelApplication) {
            $this->app->booting(function () {
                $loader = AliasLoader::getInstance();
                $loader->alias('Geocode', GeocodeFacade::class);
            });
        }

        $this->mergeConfigFrom(__DIR__.'/../config/geocode.php', 'geocode');
    }

    /**
     * Private function to configure the driver from the config.
     * The fallback driver is nominatim openstreetmap.
     *
     * @return Driver
     */
    private function configureDriver(): Driver
    {
        return match (config('geocode.driver')) {
            'google' => new GoogleMaps((string) config('geocode.api_key')),
            default => new OpenStreetMap()
        };
    }
}
