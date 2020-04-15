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
        } else {
            $entry = $parameter->getName();
        }
        return $this->injectDependency($entry, $parameter, $ctx);
    }

    /**
     * @param $entryType
     * @param ReflectionParameter $parameter
     * @param Context $ctx
     * @return mixed
     * @throws ContextContainerException
     * @throws ReflectionException
     */
    private function injectDependency($entryType, ReflectionParameter $parameter, Context $ctx)
    {
        $entryName = $parameter->getName();

        if($entryType == Context::class) {
            return $ctx;
        }

        if($ctx->getContainer()->has($entryType)) {
            return $ctx->getContainer()->get($entryType);
        }

        if($ctx->hasVar($entryName)) {
            $default = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : '';
            $entry = $ctx->var($entryName, $default);
            return $entry;
        }

        if(class_exists($entryType)) {
            $reflector = new ReflectionClass($entryType);
            $constructor = $reflector->getConstructor();
            $methodArguments = [];
            if ($constructor instanceof ReflectionMethod) {
                foreach ($constructor->getParameters() as $parameter) {
                    $methodArguments[] = $this->prepareParameter($parameter, $ctx);
                }
                return $reflector->newInstanceArgs($methodArguments);
            } else {
                return new $entryType;
            }
        } else {
            if($ctx->getContainer()->has($entryName)) {
                return $ctx->getContainer()->get($entryName);
            } else {
                if($parameter->isDefaultValueAvailable()) {
                    return $parameter->getDefaultValue();
                }
                throw new ContextContainerException(
                    'Internal error: Failed to retrieve the default value '.
                    'for ' . $entryName . ' [Function: ' . $parameter->getDeclaringFunction() . ']'
                );
            }
        }
    }
}