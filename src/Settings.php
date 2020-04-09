<?php

namespace Askoldex\Teletant;


use Psr\Log\LoggerInterface;

class Settings
{
    private $api_token = '';

    private $base_uri = 'https://api.telegram.org/';

    private $clientOptions = [];

    /**
     * @var LoggerInterface|null $logger
     */
    private $logger = null;

    private $useHookReply = true;
    private $hookOnFirstRequest = true;
    private $useDependencyInjection = true;

    private $proxy = '';

    public function __construct(string $api_token = null)
    {
        $this->setApiToken($api_token);
    }

    /**
     * @param LoggerInterface $logger
     * @return self
     */
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @return LoggerInterface|null
     */
    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }


    /**
     * @return string
     */
    public function getApiToken() : string
    {
        return $this->api_token;
    }

    /**
     * @param string $api_token
     * @return self
     */
    public function setApiToken(string $api_token) : self
    {
        $this->api_token = $api_token;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->base_uri;
    }

    /**
     * @param string $base_uri
     * @return self
     */
    public function setBaseUri(string $base_uri) : self
    {
        $this->base_uri = $base_uri;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUseHookReply(): bool
    {
        return $this->useHookReply;
    }

    /**
     * @param bool $useHookReply
     * @return self
     */
    public function setUseHookReply(bool $useHookReply) : self
    {
        $this->useHookReply = $useHookReply;
        return $this;
    }

    /**
     * @return string
     */
    public function getProxy(): string
    {
        return $this->proxy;
    }

    /**
     * @param string $proxy
     * @return self
     */
    public function setProxy(string $proxy) : self
    {
        $this->proxy = $proxy;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHookOnFirstRequest(): bool
    {
        return $this->hookOnFirstRequest;
    }

    /**
     * @param bool $hookOnFirstRequest
     */
    public function setHookOnFirstRequest(bool $hookOnFirstRequest)
    {
        $this->hookOnFirstRequest = $hookOnFirstRequest;
    }

    /**
     * @return bool
     */
    public function isUseDependencyInjection(): bool
    {
        return $this->useDependencyInjection;
    }

    /**
     * @param bool $useDependencyInjection
     * @return self
     */
    public function setUseDependencyInjection(bool $useDependencyInjection): self
    {
        $this->useDependencyInjection = $useDependencyInjection;
        return $this;
    }

    /**
     * @return array
     */
    public function getClientOptions(): array
    {
        return $this->clientOptions;
    }

    /**
     * @param array $clientOptions
     * @return self
     */
    public function setClientOptions(array $clientOptions): self
    {
        $this->clientOptions = $clientOptions;
        return $this;
    }
}