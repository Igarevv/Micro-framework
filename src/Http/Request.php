<?php

namespace Ihor\MicroFramework\Http;

class Request
{
    public function __construct(
      private readonly array $post,
      private readonly array $get,
      private readonly array $server,
      private readonly array $cookies,
      private readonly array $files,
    ) {}

    public static function createFromGlobals(): Request
    {
        return new self($_POST, $_GET, $_SERVER, $_COOKIE, $_FILES);
    }

    public function post(string $key, $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    public function get(string $key, $default = null): mixed
    {
        return $this->get[$key] ?? $default;
    }

    public function getUriPath(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function getUrlQuery(): string
    {
        return $this->server['QUERY_STRING'] ?? '';
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

}