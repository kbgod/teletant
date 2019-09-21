<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class OrderInfo extends Entity
{
    public function name(): ?string
    {
        return parent::_data('name');
    }

    public function phoneNumber(): ?string
    {
        return parent::_data('phone_number');
    }

    public function email(): ?string
    {
        return parent::_data('email');
    }

    public function shippingAddress(): ShippingAddress
    {
        return new ShippingAddress(parent::_data('shipping_address'));
    }
}