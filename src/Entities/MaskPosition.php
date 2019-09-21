<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class MaskPosition extends Entity
{
    public function point(): ?string
    {
        return parent::_data('point');
    }

    public function xShift(): ?float
    {
        return parent::_data('x_shift');
    }

    public function yShift(): ?float
    {
        return parent::_data('y_shift');
    }

    public function scale(): ?float
    {
        return parent::_data('scale');
    }
}