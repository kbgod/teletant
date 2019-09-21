<?php


namespace Askoldex\Teletant\Middleware;


use Askoldex\Teletant\Context;

trait EventDispatcher
{
    protected $preparedMiddlewares;
    protected $currentMiddlewares = [];
    private $eventMiddlewares;
    private $_eventHandler;
    private $eventIndex;

    /**
     * @param array $middlewares
     * @param callable $eventHandler
     * @return $this
     */
    public function bootEvent(array $middlewares, callable $eventHandler)
    {
        $this->eventMiddlewares = $middlewares;
        $this->_eventHandler = $eventHandler;
        $this->eventIndex = 0;
        return $this;
    }

    public function handleEvent(Context $ctx)
    {
        $middleware = $this->getEventMiddleware();
        $this->eventIndex++;
        if (is_null($middleware)) {
            return ($this->_eventHandler)($ctx);
        } else
            return $middleware($ctx, [$this, 'handleEvent']);
    }

    /**
     * @return callable|null
     */
    private function getEventMiddleware(): ?callable
    {
        if (isset($this->eventMiddlewares[$this->eventIndex])) {
            return $this->eventMiddlewares[$this->eventIndex];
        }
        return null;
    }

    public function eventMiddlewares(array $middlewares) {
        $this->preparedMiddlewares = $middlewares;
    }

    private function getMiddlewares(string $middlewares)
    {
        $middlewares = explode(',', $middlewares);
        $selectedMiddlewares = [];
        foreach ($middlewares as $middleware) {
            $selected = $this->preparedMiddlewares[$middleware];
            if(is_callable($selected)) $selected = [$selected];
            $selectedMiddlewares = array_merge($selectedMiddlewares, $selected);
        }
        return $selectedMiddlewares;
    }

    public function em(string $middlewares, callable $call)
    {
        $this->currentMiddlewares = $this->getMiddlewares($middlewares);
        $call($this);
        $this->currentMiddlewares = [];
    }
}