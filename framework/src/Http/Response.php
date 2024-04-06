<?php

namespace Igarevv\MicroFramework\Http;

class Response
{

    public function __construct(
      private mixed $content,
      private int $status = 200,
    ) {}

    public function send()
    {
        http_response_code($this->status);

        echo $this->content;
    }

}