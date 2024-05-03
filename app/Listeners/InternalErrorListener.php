<?php

namespace App\Listeners;

use Igarevv\Micrame\Http\Events\ResponseEvent;

class InternalErrorListener
{
    public function __invoke(ResponseEvent $event): void
    {
        if ($event->getResponse()->getStatusCode() >= 500){
            $event->stop();
        }
    }

}