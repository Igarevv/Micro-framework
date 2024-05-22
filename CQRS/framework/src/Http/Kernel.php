<?php

namespace Igarevv\Micrame\Http;

use Clockwork\Support\Vanilla\Clockwork;
use Exception;
use Igarevv\Micrame\Enums\HttpEnum;
use Igarevv\Micrame\Events\EventDispatcher;
use Igarevv\Micrame\Exceptions\Http\HttpException;
use Igarevv\Micrame\Http\Events\ResponseEvent;
use Igarevv\Micrame\Http\Middleware\RequestHandlerInterface;
use Igarevv\Micrame\Http\Request\RequestInterface;
use Igarevv\Micrame\Http\Response\Response;
use Igarevv\Micrame\Session\Session;
use Psr\Container\ContainerInterface;
use Throwable;

class Kernel
{

    private string $appStatus = 'local';

    public function __construct(
      private readonly ContainerInterface $container,
      private readonly RequestInterface $request,
      private readonly RequestHandlerInterface $handler,
      private readonly EventDispatcher $eventDispatcher
    ) {
        $this->appStatus = $this->container->get('APP_ENV');
    }

    public function handle(): Response
    {
        try {
            $response = $this->handler->handle($this->request);
        } catch (Exception|Throwable $e) {
            $response = $this->handleErrorByAppStatus($e);
        }

        $this->eventDispatcher->dispatch(new ResponseEvent($this->request, $response));

        return $response;
    }

    public function cleanUp(RequestInterface $request): void
    {
        if ($request->session()?->hasFlash(Session::FLASH)){
            $request->session()?->clearFlash(Session::FLASH);
        }
    }

    private function handleErrorByAppStatus(Throwable|Exception $e): Response
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