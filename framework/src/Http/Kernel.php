<?php

namespace Igarevv\Micrame\Http;

use Igarevv\Micrame\Exceptions\Http\HttpException;
use Igarevv\Micrame\Router\RouterInterface;
use Psr\Container\ContainerInterface;

class Kernel
{

    private string $appStatus = 'local';

    public function __construct(
      private RouterInterface $router,
      private ContainerInterface $container,
      private RequestInterface $request
    ) {
        $this->appStatus = $this->container->get('APP_ENV');
    }

    public function handle(): Response
    {
        try {
            [$handler, $args] = $this->router->dispatch($this->request,
              $this->container);

            $response = call_user_func_array($handler, $args);
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
        return new Response('500 | Server Error', 500);
    }

}