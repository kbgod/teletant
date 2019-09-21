<?php

namespace Askoldex\Teletant\Middleware;


use Askoldex\Teletant\Context;

trait Dispatcher
{
    protected $middlewares = [];
    protected $index = 0;


    /**
     * @return self
     */
    public function boot(): self
    {
        $this->index = 0;
        return $this;
    }

    /**
     * @param array $middlewares
     * @return $this
     */
    public function middlewares(array $middlewares)
    {
        $this->middlewares = $middlewares;
        return $this;
    }

    public function run(Context $ctx)
    {
        $middleware = $this->getMiddleware();
        $this->index++;
        if (is_null($middleware)) {
            return ($this->eventProcessor)($ctx);
        } else
            return $middleware($ctx, [$this, 'run']);
    }

    /**
     * @return callable|null
     */
    private function getMiddleware(): ?callable
    {
        if (isset($this->middlewares[$this->index])) {
            return $this->middlewares[$this->index];
        }
        return null;
    }
}