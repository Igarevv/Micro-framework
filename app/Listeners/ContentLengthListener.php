<?php

namespace App\Listeners;

use Igarevv\Micrame\Http\Events\ResponseEvent;

class ContentLengthListener
{
    public function __invoke(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        if (! array_key_exists('Content-Length', $response->getHeader())){
            $response->setHeader('Content-Length', strlen($response->getContent()));
        }
    }

}