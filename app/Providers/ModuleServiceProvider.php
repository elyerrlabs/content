<?php

namespace Content\App\Providers;

use Content\App\Support\Module;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider as Provider;

class ModuleServiceProvider extends Provider
{
    use Module;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMigrations();
        $this->registerConfigs();
    }

    public function boot()
    {
        $this->registerBladeComponents();
        $this->registerMiddlewares();
        $this->loadViewsFrom(__DIR__ . "/../../resources/views", $this->generateViewPrefix());
        $this->registerCommands();
        $this->registerSchedules();
    }

    /**
     * Merge configs
     * @return void
     */
    public function registerConfigs()
    {
        $configPath = __DIR__ . "/../../config";
        $moduleName = $this->getModuleName();

        if (!is_dir($configPath)) {
            return;
        }

        foreach (glob($configPath . "/*.php") as $file) {
            // Config file name
            $key = basename($file, '.php');

            $currentConfig = config($key, []);

            $filePath = include $file;

            // Check empty files
            if (count($filePath) == 0) {
                continue;
            }

            // Merge configs
            switch ($key) {
                case 'rate_limit':
                    $moduleConfig = [];
                    $moduleConfig['third-party'][$moduleName] = include $file;
                    $merged = $this->mergeConfigSmart($moduleConfig, $currentConfig);
                    config()->set($key, $merged);
                    break;
                case 'routes':
                    $moduleConfig = [];
                    $moduleConfig['third-party'][$moduleName] = include $file;
                    $merged = $this->mergeConfigSmart($moduleConfig, $currentConfig);
                    config()->set($key, $merged);
                    break;
                case 'module':
                    $moduleConfig = [];
                    $moduleConfig['third-party'][$moduleName] = include $file;
                    $merged = $this->mergeConfigSmart($moduleConfig, $currentConfig);
                    config()->set($key, $merged);
                    break;
                case 'menus': // Merge Menus
                    $merged = $this->mergeConfigSmart($filePath, $currentConfig);
                    config()->set($key, $merged);
                    break;
                case 'auth':
                    $merged = $this->mergeConfigSmart($currentConfig, $filePath);
                    config()->set('auth', $merged);
                    break;
                default:
                    $this->mergeConfigFrom($file, $key);
                    break;
            }
        }
    }

    /**
     * Register middlewares
     * @return void
     */
    protected function registerMiddlewares()
    {
        $path = __DIR__ . "/../Http/kernel.php";

        $router = $this->app->make(Router::class);

        if (file_exists($path)) {
            $alias = require $path;

            foreach ($alias as $key => $value) {
                $router->aliasMiddleware($key, $value);
            }
        }
    }

    /**
     * Register the migrations for the Identity module.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . "/../../database/migrations");
    }

    /**
     * Register components
     * @return void
     */
    protected function registerBladeComponents(): void
    {
        $path = __DIR__ . '/../View/Components';

        if (!File::exists($path)) {
            return;
        }

        $files = File::allFiles($path);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $baseNamespace = "{$this->generateViewPrefix()}\\App\\View\\Components";

            $relativePath = str_replace([$path . '/', '.php'], '', $file->getPathname());

            $class = $baseNamespace . '\\' . str_replace('/', '\\', $relativePath);

            if (!class_exists($class)) {
                continue;
            }

            $reflection = new \ReflectionClass($class);

            if (
                $reflection->isAbstract() ||
                !$reflection->isSubclassOf(\Illuminate\View\Component::class)
            ) {
                continue;
            }

            $alias = str("{$this->generateViewPrefix()}{$reflection->getShortName()}")->kebab()->toString();

            Blade::component($alias, $class);
        }
    }

    /**
     * Register commands
     * @return void
     */
    protected function registerCommands(): void
    {
        $path = __DIR__ . '/../../app/Console';

        if (!is_dir($path)) {
            return;
        }

        $commands = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $path,
                \FilesystemIterator::SKIP_DOTS
            )
        );

        foreach ($iterator as $file) {

            if ($file->getExtension() !== 'php') {
                continue;
            }

            $class = $this->resolveClassFromFile(
                $file->getRealPath()
            );

            if (!$class) {
                continue;
            }

            if (
                is_subclass_of(
                    $class,
                    \Illuminate\Console\Command::class
                )
            ) {
                $commands[] = $class;
            }
        }

        $this->commands($commands);
    }

    /**
     * Resoolve classes
     * @param string $file
     * @return string|null
     */
    protected function resolveClassFromFile(string $file): ?string
    {
        $contents = file_get_contents($file);

        preg_match('/namespace\s+(.+?);/', $contents, $namespace);
        preg_match('/class\s+([^\s]+)/', $contents, $class);

        if (
            empty($namespace[1]) ||
            empty($class[1])
        ) {
            return null;
        }

        return $namespace[1] . '\\' . $class[1];
    }

    /**
     * Register schedules
     * @return void
     */
    protected function registerSchedules(): void
    {
        $path = __DIR__ . '/../../routes/console.php';

        if (!file_exists($path)) {
            return;
        }

        $callback = require $path;

        $callback(
            $this->app->make(Schedule::class)
        );
    }
}
