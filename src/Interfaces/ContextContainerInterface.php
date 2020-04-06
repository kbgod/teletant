<?php


namespace Askoldex\Teletant\Interfaces;


interface ContextContainerInterface
{
    public function bind($id, $value);

    public function singleton($id, $value);

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws ContextContainerExceptionInterface  No entry was found for **this** identifier.
     *
     * @return mixed Entry.
     */
    public function get($id);

    public function has($id);
}