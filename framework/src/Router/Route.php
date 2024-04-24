<?php

namespace Igarevv\Micrame\Router;

class Route
{

    public static function get(string $path, array|callable $action): array
    {
        return ['GET', $path, $action];
    }

    public static function post(string $path, array|callable $action): array
    {
        return ['POST', $path, $action];
    }

    public static function patch(string $path, array|callable $action): array
    {
        return ['PATCH', $path, $action];
    }

    public static function put(string $path, array|callable $action): array
    {
        return ['PUT', $path, $action];
    }

    public static function delete(string $path, array|callable $action): array
    {
        return ['DELETE', $path, $action];
    }

}