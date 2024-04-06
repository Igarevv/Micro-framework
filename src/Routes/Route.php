<?php

namespace Ihor\MicroFramework\Routes;

class Route
{
    public static function get(string $uri, array|callable $action): array
    {
        return ['GET', $uri, $action];
    }

    public static function post(string $uri, array|callable $action): array
    {
        return ['POST', $uri, $action];
    }
}