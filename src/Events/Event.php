<?php

namespace Askoldex\Teletant\Events;


use Askoldex\Teletant\Context;

class Event
{
    /**
     * @var callable $eventFunction
     */
    private $eventFunction;

    public function __construct(callable $eventFunction)
    {
        $this->eventFunction = $eventFunction;
    }

    public function invoke(Context $ctx): bool
    {
        return ($this->eventFunction)($ctx);
    }
}