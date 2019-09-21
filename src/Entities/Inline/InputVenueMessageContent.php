<?php

namespace Askoldex\Teletant\Entities\Inline;


use Askoldex\Teletant\Entities\Inline\Base\InputMessageContent;

class InputVenueMessageContent extends InputMessageContent
{
    public function latitude($latitude)
    {
        $this->out['latitude'] = $latitude;
        return $this;
    }

    public function longitude($longitude)
    {
        $this->out['longitude'] = $longitude;
        return $this;
    }

    public function title($title)
    {
        $this->out['title'] = $title;
        return $this;
    }

    public function address($address)
    {
        $this->out['address'] = $address;
        return $this;
    }

    public function foursquareID($foursquareID)
    {
        $this->out['foursquare_id'] = $foursquareID;
        return $this;
    }

    public function foursquareType($foursquareType)
    {
        $this->out['foursquare_type'] = $foursquareType;
        return $this;
    }
}