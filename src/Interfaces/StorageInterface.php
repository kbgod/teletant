<?php

namespace Askoldex\Teletant\Interfaces;


interface StorageInterface
{
    /**
     * @param string $sceneName
     * @return mixed
     */
    public function setScene(string $sceneName);

    /**
     * @return string
     */
    public function getScene(): string;

    /**
     * @param string $sceneName
     * @param int $seconds
     * @return mixed
     */
    public function setTtl(string $sceneName, int $seconds);

    /**
     * @param string $sceneName
     * @return mixed
     */
    public function getTtl(string $sceneName);
}