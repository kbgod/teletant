<?php

namespace Askoldex\Teletant\Entities\Inline;


use Askoldex\Teletant\Entities\Inline\Base\InlineQueryResult;

class Contact extends InlineQueryResult
{
    public function __construct($id)
    {
        $this->out['type'] = 'contact';
        $this->out['id'] = $id;
    }

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

    public function thumbUrl($thumb_url)
    {
        $this->out['thumb_url'] = $thumb_url;
        return $this;
    }

    public function thumbWidth($thumb_width)
    {
        $this->out['thumb_width'] = $thumb_width;
        return $this;
    }

    public function thumbHeight($thumb_height)
    {
        $this->out['thumb_height'] = $thumb_height;
        return $this;
    }
}