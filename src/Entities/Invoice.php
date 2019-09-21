<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Invoice extends Entity
{
    public function title(): ?string
    {
        return parent::_data('title');
    }

    public function description(): ?string
    {
        return parent::_data('description');
    }

    public function startParameter(): ?string
    {
        return parent::_data('start_parameter');
    }

    public function currency(): ?string
    {
        return parent::_data('currency');
    }

    public function totalAmount(): ?int
    {
        return parent::_data('total_amount');
    }
}