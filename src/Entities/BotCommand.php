<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class BotCommand extends Entity
{
    public function command(): ?string
    {
        return parent::_data('command');
    }

    public function description(): ?string
    {
        return parent::_data('description');
    }
}