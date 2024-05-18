<?php

namespace Igarevv\Micrame\View;

use Igarevv\Micrame\Session\AuthSession;
use Igarevv\Micrame\Session\SessionInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{

    public function __construct(
      private readonly string $pathToViews,
      private readonly SessionInterface $session,
      private readonly AuthSession $auth
    ) {}

    public function initialize(): Environment
    {
        $loader = new FilesystemLoader($this->pathToViews);

        $twig = new Environment($loader, [
          'debug' => true,
          'cache' => false,
        ]);

        $twig->addExtension(new DebugExtension());
        $twig->addFunction(new TwigFunction('session', [$this, 'getSession']));
        $twig->addFunction(new TwigFunction('auth', [$this, 'getAuth']));

        return $twig;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    public function getAuth(): AuthSession
    {
        return $this->auth;
    }

}