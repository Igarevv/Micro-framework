<?php

namespace Igarevv\Micrame\Controller;

use Igarevv\Micrame\Http\Response;
use Psr\Container\ContainerInterface;
use Twig\Environment;

abstract class Controller
{
    private ?ContainerInterface $container = null;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function container(): ContainerInterface
    {
        return $this->container;
    }

    public function render(string $path, array $arguments = []): Response
    {
        /** @var Environment $twig */
        $twig = $this->container->get('twig');

        $content = $twig->render($path, $arguments);

        return new Response($content);
    }
}