<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class EncryptedPassportElement extends Entity
{
    public function type(): ?string
    {
        return parent::_data('type');
    }

    public function data(): ?string
    {
        return parent::_data('data');
    }

    public function phoneNumber(): ?string
    {
        return parent::_data('phone_number');
    }

    public function email(): ?string
    {
        return parent::_data('email');
    }

    /**
     * @return PassportFile[]
     */
    public function files(): array
    {
        $files = parent::_data('files');
        if (!is_null($files)) {
            foreach ($files as $key => $data) {
                $files[$key] = new PassportFile($data);
            }
            return $files;
        } else return [];
    }

    /**
     * @return PassportFile[]
     */
    public function frontSide(): array
    {
        $files = parent::_data('front_side');
        if (!is_null($files)) {
            foreach (parent::_data('front_side') as $key => $data) {
                $files[$key] = new PassportFile($data);
            }
            return $files;
        } else return [];
    }

    /**
     * @return PassportFile[]
     */
    public function reverseSide(): array
    {
        $files = parent::_data('reverse_side');
        if (!is_null($files)) {
            foreach ($files as $key => $data) {
                $files[$key] = new PassportFile($data);
            }
            return $files;
        } else return [];
    }

    /**
     * @return PassportFile[]
     */
    public function selfie(): array
    {
        $selfies = parent::_data('selfie');
        if (!is_null($selfies)) {
            foreach (parent::_data('selfie') as $key => $selfie) {
                $selfies[$key] = new PassportFile($selfie);
            }
            return $selfies;
        } else return [];
    }

    /**
     * @return PassportFile[]
     */
    public function translation(): array
    {
        $translations = parent::_data('translation');
        if (!is_null($translations)) {
            foreach ($translations as $key => $translation) {
                $translations[$key] = new PassportFile($translation);
            }
            return $translations;
        } else return [];
    }

    public function hash(): ?string
    {
        return parent::_data('hash');
    }
}