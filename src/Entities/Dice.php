<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Dice extends Entity
{
    /**
     * @return int|null
     */
    public function value(): ?int
    {
        return parent::_data('value');
    }
}