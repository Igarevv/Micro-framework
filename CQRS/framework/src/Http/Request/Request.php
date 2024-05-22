<?php

namespace Igarevv\Micrame\Http\Request;

use Igarevv\Micrame\Exceptions\Http\HttpException;
use Igarevv\Micrame\Session\SessionInterface;

final class Request implements RequestInterface
{

    private SessionInterface $session;

    private $handler;

    private array $routeArgs;

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

    public function getPost(array|string $keys = []): mixed
    {
        if ( ! $keys) {
            return $this->post;
        }

        if (is_array($keys)) {
            return array_intersect_key($this->post, array_flip($keys));
        }

        return $this->post[$keys] ?? [];
    }

    public function getGet(array|string $keys = []): mixed
    {
        if ( ! $keys) {
            return $this->get;
        }

        if (is_array($keys)) {
            return array_intersect_key($this->get, array_flip($keys));
        }

        return $this->get[$keys] ?? [];
    }

    public function getFile(?string $key = null): array
    {
        if ($key === null) {
            throw new HttpException('File key not provided');
        }

        if (! isset($this->files[$key])) {
            throw new HttpException("File with key {$key} not found");
        }

        $this->validateFile($this->files[$key]);

        return $this->files[$key];
    }

    public function getFiles(?string $key = null): array
    {
        if ($key === null) {
            return $this->files;
        }

        if (!isset($this->files[$key])) {
            throw new HttpException("Files with key {$key} not found");
        }

        foreach ($this->files[$key]['error'] as $index => $error) {
            if ($error === UPLOAD_ERR_NO_FILE) {
                throw new HttpException("File not provided");
            }
            if ($error !== UPLOAD_ERR_OK) {
                throw new HttpException("Error uploading file {$this->files[$key]['name'][$index]} with error code {$error}");
            }
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

    public function setRouteHandler(array|callable $handler): void
    {
        $this->handler = $handler;
    }

    public function setRouteArgs(array $args): void
    {
        $this->routeArgs = $args;
    }

    public function getRouteHandlerAndArgs(): array
    {
        return [$this->handler, $this->routeArgs];
    }

    private function validateFile(array $file): void
    {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            throw new HttpException('File not provided');
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new HttpException("Error uploading file {$file['name']} with error code {$file['error']}");
        }
    }

}