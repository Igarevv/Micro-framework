<?php

namespace Igarevv\Micrame\Events;

interface StoppableEventInterface
{

    public function isPropagationStopped(): bool;

}