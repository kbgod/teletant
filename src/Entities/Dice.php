<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Dice extends Entity
{
    /**
     * @return string|null
     */
    public function emoji(): ?string
    {
        return parent::_data('emoji');
    }

    /**
     * @return bool
     */
    public function isDice(): bool
    {
        return parent::_data('emoji') == "\xF0\x9F\x8E\xB2";
    }

    /**
     * @return bool
     */
    public function isDarts(): bool
    {
        return parent::_data('emoji') == "\xF0\x9F\x8E\xAF";
    }

    /**
     * @return bool
     */
    public function isSlotMachine(): bool
    {
        return parent::_data('emoji') == "\xF0\x9F\x8E\xB0";
    }

    protected $slots = [
        0 => 'bar',
        1 => 'grape',
        2 => 'lemon',
        3 => 'seven',
    ];

    public function getSlots(): Slots
    {
        return new Slots($this->value());
    }

    /**
     * @return int|null
     */
    public function value(): ?int
    {
        return parent::_data('value');
    }
}