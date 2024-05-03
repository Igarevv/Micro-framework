<?php

namespace App\Providers;

use App\Listeners\ContentLengthListener;
use App\Listeners\InternalErrorListener;
use Igarevv\Micrame\Events\EventDispatcher;
use Igarevv\Micrame\Http\Events\ResponseEvent;

class ServiceProvider implements ProviderInterface
{

    private array $listeners = [
      ResponseEvent::class => [
        InternalErrorListener::class,
        ContentLengthListener::class
      ]
    ];

    public function __construct(
      private EventDispatcher $dispatcher
    ) {}

    public function register(): void
    {
        foreach ($this->listeners as $event => $listeners){
            foreach ($listeners as $listener){
                $this->dispatcher->addListener($event, new $listener);
            }
        }
    }

}