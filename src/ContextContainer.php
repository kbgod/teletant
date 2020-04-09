<?php


namespace Askoldex\Teletant;


use Askoldex\Teletant\Exception\ContextContainerException;
use Askoldex\Teletant\Interfaces\ContextContainerInterface;

class ContextContainer implements ContextContainerInterface
{

    private $container = [];

    /**
     * @var Context $ctx
     */
    private $ctx;

    public function __construct(Context $ctx)
    {
        $this->ctx = $ctx;
    }

    public function bind($id, $value)
    {
        $this->container[$id] = $value;

        return $this;
    }

    public function singleton($id, $value)
    {
        if (is_callable($value)) {
            $this->container[$id] = $value($this->ctx);
        }

        return $this;
    }

    /**
     * @param string $id
     * @return mixed
     * @throws ContextContainerException
     */
    public function get($id)
    {
        if ($this->has($id)) {
            $entry = $this->container[$id];
            return $this->prepareEntry($entry);
        } else {
            throw new ContextContainerException("No entry was found for '{$id}' identifier.");
        }
    }

    public function has($id)
    {
        return array_key_exists($id, $this->container);
    }

    /**
     * @param mixed $entry
     * @return mixed
     */
    private function prepareEntry($entry)
    {
        if (is_callable($entry)) {
            return $entry($this->ctx);
        }

        return $entry;
    }
}