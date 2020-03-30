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

    public function getResponse(): ?TeletantResponse
    {
        return $this->response instanceof TeletantResponse ? $this->response : null;
    }

    protected function _data($param = null)
    {
        if($param == null) return $this->__data;
        return $this->__data[$param] ?? null;
    }

    public function isEmpty()
    {
        return empty($this->__data);
    }

    public function has($field)
    {
        return array_key_exists($field, $this->__data);
    }

    public function getField($field, $default = '')
    {
        return $this->has($field) ? $field : $default;
    }

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