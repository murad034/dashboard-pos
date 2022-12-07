<?php

namespace App\Helpers;

use Dotenv\Repository\Adapter\ImmutableWriter;
use Dotenv\Repository\AdapterRepository;
use Illuminate\Support\Env;

class DynamicEnvironment
{
    public static function set(string $key, string $value)
    {
        $closure_adapter = \Closure::bind(function &(AdapterRepository $class) {
            $closure_writer = \Closure::bind(function &(ImmutableWriter $class) {
                return $class->writer;
            }, null, ImmutableWriter::class);
            return $closure_writer($class->writer);
        }, null, AdapterRepository::class);
        return $closure_adapter(Env::getRepository())->write($key, $value);
    }
    /**
     * @param string $key
     * @param string $value
     */
    public static function setEnvValue(string $key, string $value)
    {
        $path = app()->environmentFilePath();
        $env = file_get_contents($path);

        $old_value = env($key);

        if (!str_contains($env, $key.'=')) {
            $env .= sprintf("%s=%s\n", $key, $value);
        } else if ($old_value) {
            $env = str_replace(sprintf('%s=%s', $key, $old_value), sprintf('%s=%s', $key, $value), $env);
        } else {
            $env = str_replace(sprintf('%s=', $key), sprintf('%s=%s',$key, $value), $env);
        }

        file_put_contents($path, $env);
    }
}
