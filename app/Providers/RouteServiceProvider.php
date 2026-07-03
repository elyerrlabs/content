<?php

namespace Content\App\Providers;

use Content\App\Support\Module;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as Provider;

final class RouteServiceProvider extends Provider
{

    use Module;

    /**
     * Register 
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot
     * @return void
     */
    public function boot()
    {
        $path = __DIR__ . "/../../routes";
        $name = $this->getModuleName();

        Route:: as("module.{$name}.")->group(
            function () use ($name, $path) {

                // Admin routes
                if (file_exists($path . '/admin.php')) {
                    Route::group([
                        'prefix' => "$name/admin",
                        'as' => "admin.",
                        'middleware' => ['web'],
                        'module' => $this->generateViewPrefix(), // DO NOT REMOVE
                        'config_key' => $this->getConfigKey(), // DO NOT REMOVE
                        'module_type' => 'third-party', // DO NOT REMOVE
                        'module_path' => $this->moduleBasePath() // DO NOT REMOVE
                    ], fn() => require $path . '/admin.php');
                }

                // API routes
                if (file_exists($path . '/api.php')) {
                    Route::group([
                        'prefix' => "$name/api",
                        'as' => 'api.',
                        'middleware' => ['api'],
                        'module' => $this->generateViewPrefix(), // DO NOT REMOVE
                        'config_key' => $this->getConfigKey(), // DO NOT REMOVE
                        'module_type' => 'third-party', // DO NOT REMOVE
                        'module_path' => $this->moduleBasePath() // DO NOT REMOVE
                    ], fn() => require $path . '/api.php');
                }

                if (file_exists($path . '/web.php')) {
                    Route::group([
                        'prefix' => $name,
                        'as' => 'web.',
                        'middleware' => ['web'],
                        'module' => $this->generateViewPrefix(), // DO NOT REMOVE
                        'config_key' => $this->getConfigKey(), // DO NOT REMOVE
                        'module_type' => 'third-party', // DO NOT REMOVE
                        'module_path' => $this->moduleBasePath() // DO NOT REMOVE
                    ], fn() => require $path . '/web.php'); // DO NOT REMOVE  
                }

                if (file_exists($path . '/public.php')) {
                    Route::group([
                        'as' => 'public.',
                        'middleware' => ['web'],
                        'module' => $this->generateViewPrefix(), // DO NOT REMOVE
                        'config_key' => $this->getConfigKey(), // DO NOT REMOVE
                        'module_type' => 'third-party', // DO NOT REMOVE
                        'module_path' => $this->moduleBasePath() // DO NOT REMOVE
                    ], fn() => require $path . '/public.php');// DO NOT REMOVE
                }
            }
        );
    }
}
