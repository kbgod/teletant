<?php

namespace Askoldex\Teletant\Interfaces;


interface StorageInterface
{
    public function setScene(string $sceneName);

    public function getScene(): string;

    public function setTtl(string $sceneName, int $seconds);

    public function getTtl(string $sceneName);
}