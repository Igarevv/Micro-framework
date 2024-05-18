<?php

namespace Igarevv\Micrame\Http\Events;

use Igarevv\Micrame\Events\Event;
use Igarevv\Micrame\Http\Request\RequestInterface;
use Igarevv\Micrame\Http\Response\Response;

class ResponseEvent extends Event
{
    public function __construct(
      private RequestInterface $request,
      private Response $response
    ) {}

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

}