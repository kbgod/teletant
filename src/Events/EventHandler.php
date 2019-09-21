<?php

namespace Askoldex\Teletant\Events;


class EventHandler
{
    /**
     * @var Event[] $events
     */
    private $events;

    /**
     * @param callable $handler
     * @return Event
     */
    public function handle(callable $handler): Event
    {
        $this->events[] = $event = new Event($handler);
        return $event;
    }

    /**
     * @return Event[]
     */
    public function getEvents()
    {
        return $this->events;
    }
}