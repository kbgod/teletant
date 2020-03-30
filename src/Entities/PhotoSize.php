<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class PhotoSize extends Entity
{
    public function fileId(): ?string
    {
        return parent::_data('file_id');
    }

    public function fileUniqueId(): ?string
    {
        return parent::_data('file_unique_id');
    }

    public function width(): ?int
    {
        return parent::_data('width');
    }

    public function height(): ?int
    {
        return parent::_data('height');
    }

    public function fileSize(): ?int
    {
        return parent::_data('file_size');
    }
}