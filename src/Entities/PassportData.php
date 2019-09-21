<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class PassportData extends Entity
{
    /**
     * @return EncryptedPassportElement[]
     */
    public function data(): ?array
    {
        $passportElements = parent::_data('data');
        if (!is_null($passportElements)) {
            foreach ($passportElements as $key => $data) {
                $passportElements[$key] = new EncryptedPassportElement($data);
            }
            return $passportElements;
        } else return null;
    }

    public function credentials(): EncryptedCredentials
    {
        return new EncryptedCredentials(parent::_data('credentials'));
    }
}