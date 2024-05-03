<?php

namespace Igarevv\Micrame\Http\Response;

class Response
{

    public function __construct(
      protected string $content = '',
      protected int $status = 200,
      protected array $headers = []
    ) {}

    public function send(): void
    {
        http_response_code($this->status);

        ob_start();

        foreach ($this->headers as $key => $header) {
            header("{$key}:{$header}");
        }

        echo $this->content;

        ob_end_flush();
    }

    public function json(): Response
    {
        $this->content = json_encode($this->content, JSON_THROW_ON_ERROR);

        return $this;
    }

    public function getHeader(?string $key = null): string|array
    {
        if (! $key){
            return $this->headers;
        }

        return $this->headers[$key];
    }

    public function setHeader(string $key, mixed $value): void
    {
        $this->headers[$key] = $value;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getStatusCode(): int
    {
        return $this->status;
    }

}