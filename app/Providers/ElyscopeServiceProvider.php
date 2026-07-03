<?php

namespace Content\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Content\App\Support\Module;
use RuntimeException;

class ElyscopeServiceProvider extends ServiceProvider
{
    use Module;

    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            require_once __DIR__ . '/../../vendor-build/autoload.php';
        } catch (\Throwable $th) {

            if ($this->app->environment('production', 'staging')) {
                Log::warning(sprintf(
                    'Module "%s" is missing its compiled vendor dependencies. The "vendor-build" directory was not found. Run "elymod install" from the module root to install and build the required dependencies.',
                    $this->getModuleName()
                ), $th->getTrace());
                return;
            }

            if ($this->app->runningInConsole()) {
                return;
            }

            throw new RuntimeException(
                sprintf(
                    'Module "%s" is missing its compiled vendor dependencies. The "vendor-build" directory was not found. Run "elymod install" from the module root to install and build the required dependencies.',
                    $this->getModuleName()
                ),
                $th->getCode(),
                $th
            );
        }
    }
}
