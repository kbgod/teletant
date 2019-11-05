<?php

namespace Askoldex\Teletant\States;


use Askoldex\Teletant\Context;
use Askoldex\Teletant\Events\Event;
use Askoldex\Teletant\Events\EventBuilder;

class Scene
{
    use EventBuilder;

    /**
     * @var Event|null
     */
    private $enter = null;

    /**
     * @var Event|null
     */
    private $leave = null;

    /**
     * @var string
     */
    private $name;

    /**
     * Scene constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->bootEventBuilder();
    }

    public function getName()
    {
        return $this->name;
    }

    public function onEnter($func)
    {
        $this->enter = new Event(
            function(Context $ctx) use ($func) {
                $func($ctx);
                return true;
            }
        );
    }

    public function onLeave($func)
    {
        $this->leave = new Event(
            function(Context $ctx) use ($func) {
                $func($ctx);
                return true;
            }
        );
    }

    public function handleEnter(Context $ctx)
    {
        if($this->enter instanceof Event)
            $this->enter->invoke($ctx);
    }

    public function handleLeave(Context $ctx)
    {
        if($this->leave instanceof Event)
            $this->leave->invoke($ctx);
    }
}