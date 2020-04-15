<?php


namespace Askoldex\Teletant\Addons;

use Askoldex\Teletant\Context;
use Askoldex\Teletant\Interfaces\StorageInterface;

class LocalStorage implements StorageInterface {

    private $storage;
    /**
     * @var Context
     */
    private $ctx;


    public function boot(Context $ctx)
    {
        $this->ctx = $ctx;
        return $this;
    }

    public function setScene(string $sceneName)
    {
        $this->storage[$this->ctx->getUserID()]['scene']['name'] = $sceneName;
    }

    public function getScene(): string
    {
        $scene = $this->storage[$this->ctx->getUserID()]['scene']['name'];
        return $scene == null ? '' : $scene;
    }

    public function setTtl(string $sceneName, int $seconds)
    {
        $this->storage[$this->ctx->getUserID()]['scene']['ttl'] = $seconds;
    }

    public function getTtl(string $sceneName)
    {
        return $this->storage[$this->ctx->getUserID()]['scene']['ttl'];
    }
}