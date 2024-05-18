<?php

namespace Igarevv\Micrame\Events;

interface ListenerProviderInterface
{

    public function getListenersForEvent(object $event): iterable;

}