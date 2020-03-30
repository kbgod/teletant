<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class MessageEntity extends Entity
{

    public function type(): ?string
    {
        return parent::_data('type');
    }

    public function offset(): ?int
    {
        return parent::_data('offset');
    }

    public function length(): ?int
    {
        return parent::_data('length');
    }

    public function url(): ?string
    {
        return parent::_data('url');
    }

    public function user(): User
    {
        return new User(parent::_data('user'));
    }

    public function language(): ?string
    {
        return parent::_data('language');
    }
}