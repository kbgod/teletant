<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Contact extends Entity
{
    public function phoneNumber(): ?string
    {
        return parent::_data('phone_number');
    }

    public function firstName(): ?string
    {
        return parent::_data('first_name');
    }

    public function lastName(): ?string
    {
        return parent::_data('last_name');
    }

    public function fullName(): ?string
    {
        return $this->firstName() . ($this->lastName() != null ? ' ' . $this->lastName() : '');
    }

    public function userId(): ?int
    {
        return parent::_data('user_id');
    }

    public function vcard(): ?string
    {
        return parent::_data('vcard');
    }
}