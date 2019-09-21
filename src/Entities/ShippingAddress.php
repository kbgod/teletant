<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class ShippingAddress extends Entity
{
    public function countryCode(): ?string
    {
        return parent::_data('country_code');
    }

    public function state(): ?string
    {
        return parent::_data('state');
    }

    public function city(): ?string
    {
        return parent::_data('city');
    }

    public function streetLine1(): ?string
    {
        return parent::_data('street_line1');
    }

    public function streetLine2(): ?string
    {
        return parent::_data('street_line2');
    }

    public function postCode(): ?string
    {
        return parent::_data('post_code');
    }
}