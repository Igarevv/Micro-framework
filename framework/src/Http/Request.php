<?php

namespace Igarevv\Micrame\Http;

final class Request implements RequestInterface
{

    public function __construct(
      private readonly array $post,
      private readonly array $get,
      private readonly array $server,
      private readonly array $cookie,
      private readonly array $files
    ) {}

    public static function createFromGlobals(): self
    {
        return new self($_POST, $_GET, $_SERVER, $_COOKIE, $_FILES);
    }

    public function getUri(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

}