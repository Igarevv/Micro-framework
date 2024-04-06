<?php

namespace Igarevv\MicroFramework\Http;

use Igarevv\MicroFramework\Http\Exceptions\HttpException;
use Igarevv\MicroFramework\Routes\RouteInterface;
use League\Container\Container;

class Kernel
{

    private string $environment;

    public function __construct(
      private RouteInterface $router,
      private Request $request,
      private Container $container
    ) {
        $this->environment = $this->container->get('APP_ENV');
    }

    public function handle(): Response
    {
        try {
            [$handler, $arguments] = $this->router->dispatch($this->request,
              $this->container);

            $response = call_user_func_array($handler, $arguments);
        } catch (\Exception $e) {
            $response = $this->displayErrorsByEnv($e);
        }

        return $response;
    }

    private function displayErrorsByEnv(\Exception $e): Response
    {
        if (in_array($this->environment, ['dev', 'test', 'local'])) {
            throw $e;
        }
        if ($e instanceof HttpException) {
            return new Response($e->getMessage(), $e->getCode());
        }

        return new Response('Server error', 500);
    }

}