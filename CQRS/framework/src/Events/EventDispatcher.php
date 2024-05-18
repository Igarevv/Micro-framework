<?php

namespace Igarevv\Micrame\Events;

class EventDispatcher implements EventDispatcherInterface, ListenerProviderInterface
{

    private array $listeners = [];

    public function dispatch(object $event): object
    {
        foreach ($this->getListenersForEvent($event) as $listener){
            if ($listener instanceof StoppableEventInterface && $listener->isPropagationStopped()){
                return $event;
            }
            $listener($event);
        }
        return $event;
    }

    public function addListener(string $event, callable $listener): static
    {
        $this->listeners[$event][] = $listener;

        return $this;
    }

    public function getListenersForEvent(object $event): iterable
    {
        $eventClassName = get_class($event);

        if (array_key_exists($eventClassName, $this->listeners)){
            return $this->listeners[$eventClassName];
        }

        return [];
    }

}