<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class User extends Entity
{

    public function id(): ?int
    {
        return parent::_data('id');
    }

    public function isBot(): ?bool
    {
        return parent::_data('is_bot');
    }

    public function firstName(): ?string
    {
        return parent::_data('first_name');
    }

    public function lastName(): ?string
    {
        return parent::_data('last_name');
    }

    public function username(): ?string
    {
        return parent::_data('username');
    }

    public function languageCode(): ?string
    {
        return parent::_data('language_code');
    }
}