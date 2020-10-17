<?php

namespace HiFolks\Milk\Utils;

class Environment
{
    /**
     * Method to get environment variable via $_ENV
     * If the env variable doesn't exist, it is returned $default set by the user.
     * @param string $parameter
     * @param string $default
     * @return mixed|string
     */
    public static function getEnv(string  $parameter, $default = "")
    {
        return $_ENV[$parameter] ?? $default;
    }
}
