<?php

namespace Igarevv\Micrame\Events;

use Igarevv\Micrame\Events\StoppableEventInterface;

abstract class Event implements StoppableEventInterface
{

    private bool $propagationStopped = false;

    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

    public function stop(): void
    {
        $this->propagationStopped = true;
    }
}