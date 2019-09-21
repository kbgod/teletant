<?php

namespace Askoldex\Teletant\Entities\Inline;


use Askoldex\Teletant\Entities\Inline\Base\InputMessageContent;

class InputContactMessageContent extends InputMessageContent
{
    public function phoneNumber($phoneNumber)
    {
        $this->out['phone_number'] = $phoneNumber;
        return $this;
    }

    public function firstName($firstName)
    {
        $this->out['first_name'] = $firstName;
        return $this;
    }

    public function lastName($lastName)
    {
        $this->out['last_name'] = $lastName;
        return $this;
    }

    public function vcard($vcard)
    {
        $this->out['vcard'] = $vcard;
        return $this;
    }
}