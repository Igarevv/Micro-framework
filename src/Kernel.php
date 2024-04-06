<?php

namespace Ihor\MicroFramework;

use Ihor\MicroFramework\Http\Exceptions\HttpException;
use Ihor\MicroFramework\Http\Request;
use Ihor\MicroFramework\Http\Response;
use Ihor\MicroFramework\Routes\Router;

class Kernel
{
    public function __construct(
      private Router $router,
      private Request $request
    ) {}

    public function handle(): Response
    {
        try {
            [$handler, $variables] = $this->router->dispatch($this->request);

            $response = call_user_func_array($handler, $variables);
        } catch (HttpException $e){
            $response = new Response($e->getMessage(), $e->getCode());
        } catch (\Throwable $e){
            $response = new Response($e->getMessage());
        }

        return $response;
    }
}