<?php


namespace Askoldex\Teletant\Middleware;


use Askoldex\Teletant\Context;
use Askoldex\Teletant\Exception\ContextContainerException;
use \ReflectionParameter;
use \ReflectionNamedType;
use \ReflectionFunction;
use \ReflectionClass;
use \ReflectionException;
use \ReflectionMethod;

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
            if($ctx->Api()->getSettings()->isUseDependencyInjection()) {
                return $this->invoke($this->_eventHandler, $ctx);
            } else {
                return ($this->_eventHandler)($ctx);
            }
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

    /**
     * @param callable $event
     * @param Context $ctx
     * @return mixed
     * @throws ReflectionException
     * @throws ContextContainerException
     */
    private function invoke(callable $event, Context $ctx)
    {
        $eventReflector = new ReflectionFunction($event);
        $parameters = $eventReflector->getParameters();
        $eventArguments = [];
        foreach ($parameters as $parameter) {
            $eventArguments[] = $this->prepareParameter($parameter, $ctx);
        }
        return $eventReflector->invokeArgs($eventArguments);
    }

    /**
     * @param ReflectionParameter $parameter
     * @param Context $ctx
     * @return mixed
     * @throws ReflectionException
     * @throws ContextContainerException
     */
    private function prepareParameter(ReflectionParameter $parameter, Context $ctx)
    {
        $parameterType = $parameter->getType();
        if($parameterType instanceof ReflectionNamedType) {
            $entry = $parameterType->getName();
            if(!class_exists($entry)) {
                if($parameter->isDefaultValueAvailable()) {
                    return $parameter->getDefaultValue();
                } else {
                    if($ctx->getContainer()->has($parameter->getName())) {
                        return $ctx->getContainer()->get($parameter->getName());
                    }
                    throw new ContextContainerException(
                        'Internal error: Failed to retrieve the default value '.
                                'for ' . $parameter->getName() . ' [Function: ' . $parameter->getDeclaringFunction() . ']'
                    );
                }
            }
        } else {
            $entry = $parameter->getName();
        }
        return $this->injectDependency($entry, $ctx);
    }

    /**
     * @param $entry
     * @param Context $ctx
     * @return mixed
     * @throws ReflectionException
     * @throws ContextContainerException
     */
    private function injectDependency($entry, Context $ctx)
    {
        if($entry == Context::class) {
            return $ctx;
        }

        if($ctx->getContainer()->has($entry)) {
            return $ctx->getContainer()->get($entry);
        }

        $reflector = new ReflectionClass($entry);
        $constructor = $reflector->getConstructor();
        $methodArguments = [];
        if ($constructor instanceof ReflectionMethod) {
            foreach ($constructor->getParameters() as $parameter) {
                $methodArguments[] = $this->prepareParameter($parameter, $ctx);
            }
            return $reflector->newInstanceArgs($methodArguments);
        } else {
            return new $entry;
        }
    }
}