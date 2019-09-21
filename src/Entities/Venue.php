<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Venue extends Entity
{
    public function location(): Location
    {
        return new Location(parent::_data('location'));
    }

    public function title(): ?string
    {
        return parent::_data('title');
    }

    public function address(): ?string
    {
        return parent::_data('address');
    }

    public function foursquareId(): ?string
    {
        return parent::_data('foursquare_id');
    }

    public function foursquareType(): ?string
    {
        return parent::_data('foursquare_type');
    }
}