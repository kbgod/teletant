<?php

namespace Askoldex\Teletant\Entities\Base;

use Askoldex\Teletant\TeletantHookResponse;
use Askoldex\Teletant\TeletantResponse;

class Entity
{
    protected $__data;
    protected $response;

    /**
     * Entity constructor.
     * @param TeletantResponse|TeletantHookResponse|array $responseOrData
     */
    public function __construct($responseOrData)
    {
        if($responseOrData instanceof TeletantResponse or $responseOrData instanceof TeletantHookResponse) {
            $this->response = $responseOrData;
            $this->__data = $this->response->getResult();
        } elseif(is_array($responseOrData)) {
            $this->__data = $responseOrData;
        }
    }

    /**
     * @return TeletantResponse|null
     */
    public function getResponse(): ?TeletantResponse
    {
        return $this->response instanceof TeletantResponse ? $this->response : null;
    }

    /**
     * @param mixed $param
     * @return array|TeletantHookResponse|TeletantResponse|mixed|null
     */
    protected function _data($param = null)
    {
        if($param == null) return $this->__data;
        return $this->__data[$param] ?? null;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->__data);
    }

    /**
     * @param $field
     * @return bool
     */
    public function has($field)
    {
        return array_key_exists($field, $this->__data);
    }

    /**
     * @param $field
     * @param string $default
     * @return string
     */
    public function getField($field, $default = '')
    {
        return $this->has($field) ? $field : $default;
    }

    /**
     * @return array|TeletantHookResponse|TeletantResponse|mixed|null
     */
    public function export()
    {
        return $this->_data();
    }

    /**
     * @return null|static
     */
    public function me(): ?self
    {
        return $this->isEmpty() ? null : $this;
    }
}