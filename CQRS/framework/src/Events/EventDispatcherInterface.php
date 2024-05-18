<?php

namespace Igarevv\Micrame\Events;

interface EventDispatcherInterface
{

    public function dispatch(object $event);

}