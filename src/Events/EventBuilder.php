<?php

namespace Askoldex\Teletant\Events;


use Askoldex\Teletant\Context;
use Askoldex\Teletant\Entities\Message;
use Askoldex\Teletant\Exception\ValidatorException;
use Askoldex\Teletant\Middleware\EventDispatcher;

trait EventBuilder
{

    use EventDispatcher;
    use Validator;

    /**
     * @var EventHandler $eventHandler
     */
    private $eventHandler;

    protected function bootEventBuilder()
    {
        $this->eventHandler = new EventHandler();
    }

    /**
     * @return EventHandler
     */
    public function eventHandler(): EventHandler
    {
        return $this->eventHandler;
    }

    /**
     * @param $message $message
     * @return array
     */
    protected function parseCommands(Message $message)
    {
        $commands = [];
        foreach ($message->entities() as $entity) {
            if ($entity->type() == 'bot_command') {
                $commands[] = mb_substr($message->text(), $entity->offset(), $entity->length());
            }
        }

        return $commands;
    }

    /**
     * @param array $matches
     * @return array
     */
    protected function parseVariables(array $matches)
    {
        $output = [];
        foreach ($matches as $k => $match) {
            if (is_string($k)) $output[$k] = $match;
        }
        return $output;
    }

    /**
     * @param string $command
     * @param callable $handler
     * @return Event
     */
    public function onCommand(string $command, callable $handler)
    {
        $current = $this->currentMiddlewares;
        return $this->eventHandler->handle(function (Context $ctx) use ($command, $handler, $current) {
            if (!$ctx->getMessage()->isEmpty()) {
                $commands = $this->parseCommands($ctx->getMessage());
                if (count($commands) > 0) {
                    foreach ($commands as $cmd) {
                        if ($cmd == '/' . $command) {
                            $this->bootEvent($current, $handler)->handleEvent($ctx);
                            return true;
                        }
                    }
                }
            }
            return false;
        });
    }

    /**
     * @param string $text
     * @param callable $handler
     * @param callable|null $validator
     * @return Event
     */
    public function onText(string $text, callable $handler, callable $validator = null)
    {
        $current = $this->currentMiddlewares;
        $data = $this->parseField($text);
        return $this->eventHandler->handle(function (Context $ctx) use ($data, $handler, $current, $validator) {
            if ($ctx->getText() == null) return false;
            foreach ($data['patterns'] as $pattern) {
                if (preg_match($this->makeRegex($pattern), $ctx->getText(), $matches)) {
                    $parsedVariables = $this->parseVariables($matches);
                    $output = $this->validateVariables($parsedVariables, $data['variables']);
                    $ctx->setVariables($output['variables']);
                    if (count($output['errors']) > 0 and is_callable($validator))
                        $validator($ctx, $output['errors']);
                    else {
                        $this->bootEvent($current, $handler)->handleEvent($ctx);
                    }
                    return true;
                }
            }
            return false;
        });
    }

    /**
     * @param string $action
     * @param callable $handler
     * @param callable|null $validator
     * @return Event
     */
    public function onAction(string $action, callable $handler, callable $validator = null)
    {
        $current = $this->currentMiddlewares;
        $data = $this->parseField($action);
        return $this->eventHandler->handle(function (Context $ctx) use ($data, $handler, $current, $validator) {
            if ($ctx->callbackQuery()->isEmpty()) return false;
            foreach ($data['patterns'] as $pattern) {
                if (preg_match($this->makeRegex($pattern), $ctx->callbackQuery()->data(), $matches)) {
                    $parsedVariables = $this->parseVariables($matches);
                    $output = $this->validateVariables($parsedVariables, $data['variables']);
                    $ctx->setVariables($output['variables']);
                    if (count($output['errors']) > 0 and is_callable($validator))
                        $validator($ctx, $output['errors']);
                    else {
                        $this->bootEvent($current, $handler)->handleEvent($ctx);
                    }
                    return true;
                }
            }
            return false;
        });
    }

    /**
     * @param $texts
     * @param callable $handler
     * @param callable|null $validator
     * @return Event
     * @throws ValidatorException
     */
    public function onHears($texts, callable $handler, callable $validator = null)
    {
        if (is_string($texts)) {
            $texts = [$texts];
        }
        if (!is_array($texts)) {
            throw new ValidatorException('Invalid type of "texts". Parameter "texts" must be a "string" or "array"');
        }
        $current = $this->currentMiddlewares;
        return $this->eventHandler->handle(function (Context $ctx) use ($texts, $handler, $current, $validator) {
            if ($ctx->getText() == null) return false;
            foreach ($texts as $text) {
                $data = $this->parseField($text);
                foreach ($data['patterns'] as $pattern) {
                    if (preg_match($this->makeRegex('/' . $pattern . '/iu', false), $ctx->getText(), $matches)) {
                        $parsedVariables = $this->parseVariables($matches);
                        $output = $this->validateVariables($parsedVariables, $data['variables']);
                        $ctx->setVariables($output['variables']);
                        if (count($output['errors']) > 0 and is_callable($validator))
                            $validator($ctx, $output['errors']);
                        else {
                            $this->bootEvent($current, $handler)->handleEvent($ctx);
                        }
                        return true;
                    }
                }
            }
            return false;
        });
    }

    /**
     * @param string $field
     * @param callable $handler
     * @return Event
     */
    public function onMessage(string $field, callable $handler)
    {
        $current = $this->currentMiddlewares;
        return $this->eventHandler->handle(function (Context $ctx) use ($field, $handler, $current) {
            if (!$ctx->getMessage()->isEmpty() and $ctx->getMessage()->has($field)) {
                $this->bootEvent($current, $handler)->handleEvent($ctx);
                return true;
            }
            return false;
        });
    }

    /**
     * @param string $field
     * @param callable $handler
     * @return Event
     */
    public function onUpdate(string $field, callable $handler)
    {
        $current = $this->currentMiddlewares;
        return $this->eventHandler->handle(function (Context $ctx) use ($field, $handler, $current) {
            if (!$ctx->update()->isEmpty() and $ctx->update()->has($field)) {
                $this->bootEvent($current, $handler)->handleEvent($ctx);
                return true;
            }
            return false;
        });
    }

    /**
     * @param string $query
     * @param callable $handler
     * @param callable|null $validator
     * @return Event
     */
    public function onInlineQuery(string $query, callable $handler, callable $validator = null)
    {
        $current = $this->currentMiddlewares;
        $data = $this->parseField($query);
        return $this->eventHandler->handle(function (Context $ctx) use ($data, $handler, $current, $validator) {
            if(!$ctx->inlineQuery()->isEmpty()) {
                foreach ($data['patterns'] as $pattern) {
                    if (preg_match($this->makeRegex($pattern), $ctx->inlineQuery()->query(), $matches)) {
                        $parsedVariables = $this->parseVariables($matches);
                        $output = $this->validateVariables($parsedVariables, $data['variables']);
                        $ctx->setVariables($output['variables']);
                        if (count($output['errors']) > 0 and is_callable($validator))
                            $validator($ctx, $output['errors']);
                        else {
                            $this->bootEvent($current, $handler)->handleEvent($ctx);
                        }
                        return true;
                    }
                }
            }
            return false;
        });
    }
}