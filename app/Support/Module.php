<?php

namespace Content\App\Support;

use Illuminate\Support\Str;

trait Module
{
    /**
     * Discovery composer json
     * @return string|null
     */
    public function composerJson()
    {
        $path = __DIR__ . "/../../composer.json";

        if (file_exists($path)) {
            return json_decode(file_get_contents($path));
        }

        return null;
    }

    /**
     * Get module name
     * @return string
     */
    private function getModuleName()
    {
        $name = $this->composerJson()?->name;

        return Str::kebab(explode('/', $name)[1]);
    }

    /**
     * Generate view prefix for example "user-account" => "UserAccount"
     * @return string
     */
    public function generateViewPrefix()
    {
        return Str::studly($this->getModuleName());
    }

    /**
     * Get module path
     * @return string|null
     */
    protected function moduleBasePath(): ?string
    {
        $path = base_path('third-party/' . $this->getModuleName());

        return is_dir($path) ? 'third-party/' . $this->getModuleName() : null;
    }

    /**
     * Get module key
     * @return string
     */
    public function getConfigKey()
    {
        return "third-party." . $this->getModuleName();
    }

    /**
     * Merge configs
     * @param array $base
     * @param array $merge
     * @return array
     */
    public function mergeConfigSmart(array $base, array $merge): array
    {
        foreach ($merge as $key => $value) {

            if (isset($base[$key]) && is_array($base[$key]) && is_array($value)) {

                if ($this->isNumericArray($base[$key]) && $this->isNumericArray($value)) {
                    $base[$key] = array_values(array_merge($base[$key], $value));
                    ksort($base);
                } else { // Associative
                    $base[$key] = $this->mergeConfigSmart($base[$key], $value);
                    ksort($base);
                }
            } else {
                $base[$key] = $value;
                ksort($base);
            }
        }
        ksort($base);
        return $base;
    }



    /**
     * Verify arrays
     * @param array $array
     * @return bool
     */
    private function isNumericArray(array $array): bool
    {
        return array_keys($array) === range(0, count($array) - 1);
    }
}
