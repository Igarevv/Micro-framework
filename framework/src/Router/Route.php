<?php

namespace Igarevv\Micrame\Router;

class Route
{

    public static function get(string $path, array|callable $action, array $middleware = []): array
    {
        return ['GET', $path, [$action, $middleware]];
    }

    public static function post(string $path, array|callable $action, array $middleware = []): array
    {
        return ['POST', $path, [$action, $middleware]];
    }

    public static function patch(string $path, array|callable $action, array $middleware = []): array
    {
        return ['PATCH', $path, $action, $middleware];
    }

    public static function put(string $path, array|callable $action, array $middleware = []): array
    {
        return ['PUT', $path, $action, $middleware];
    }

    public static function delete(string $path, array|callable $action, array $middleware = []): array
    {
        return ['DELETE', $path, $action, $middleware];
    }

}