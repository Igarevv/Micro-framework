<?php

namespace Igarevv\Micrame\Http\Request;

use Igarevv\Micrame\Exceptions\Http\HttpException;
use Igarevv\Micrame\Session\SessionInterface;

final class Request implements RequestInterface
{

    private SessionInterface $session;

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

    public function getPost(array $keys = []): array
    {
        if ( ! $keys) {
            return $this->post;
        }
        return array_intersect_key($this->post, array_flip($keys));
    }

    public function getGet(array $keys = []): array
    {
        if ( ! $keys) {
            return $this->get;
        }
        return array_intersect_key($this->get, array_flip($keys));
    }

    public function getFiles(?string $key = null): array
    {
        if ( ! $key) {
            return $this->files;
        }
        if ($this->files[$key]['error'] !== UPLOAD_ERR_OK) {
            throw new HttpException("File error ".$this->files[$key]['error']);
        }
        return $this->files[$key];
    }

    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    public function session(): SessionInterface
    {
        return $this->session;
    }

}