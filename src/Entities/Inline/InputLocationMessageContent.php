<?php

namespace Askoldex\Teletant\Entities\Inline;


use Askoldex\Teletant\Entities\Inline\Base\InputMessageContent;

class InputLocationMessageContent extends InputMessageContent
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

    public function livePeriod($livePeriod)
    {
        $this->out['live_period'] = $livePeriod;
        return $this;
    }
}