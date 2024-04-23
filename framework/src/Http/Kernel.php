<?php

namespace Igarevv\Micrame\Http;

use Igarevv\Micrame\Enums\HttpEnum;
use Igarevv\Micrame\Exceptions\Http\HttpException;
use Igarevv\Micrame\Http\Middleware\RequestHandlerInterface;
use Igarevv\Micrame\Http\Request\RequestInterface;
use Igarevv\Micrame\Http\Response\Response;
use Psr\Container\ContainerInterface;

class Kernel
{

    private string $appStatus = 'local';

    public function __construct(
      private ContainerInterface $container,
      private RequestInterface $request,
      private RequestHandlerInterface $handler
    ) {
        $this->appStatus = $this->container->get('APP_ENV');
    }

    public function handle(): Response
    {
        try {
            $response = $this->handler->handle($this->request);
        } catch (\Exception|\Throwable $e) {
            $response = $this->handleErrorByAppStatus($e);
        }

        return $response;
    }

    private function handleErrorByAppStatus(\Throwable|\Exception $e): Response
    {
        if (in_array($this->appStatus, ['local', 'dev', 'test'])) {
            throw $e;
        }

        if ($e instanceof HttpException) {
            return new Response($e->getMessage(), $e->getCode());
        }
        return new Response(HttpEnum::SERVER_ERROR->toString(), HttpEnum::SERVER_ERROR->value);
    }

}