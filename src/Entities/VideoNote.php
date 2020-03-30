<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class VideoNote extends Entity
{
    public function fileId(): ?string
    {
        return parent::_data('file_id');
    }

    public function fileUniqueId(): ?string
    {
        return parent::_data('file_unique_id');
    }

    public function length(): ?int
    {
        return parent::_data('length');
    }

    public function duration(): ?int
    {
        return parent::_data('duration');
    }

    public function thumb(): PhotoSize
    {
        return new PhotoSize(parent::_data('thumb'));
    }

    public function fileSize(): ?int
    {
        return parent::_data('file_size');
    }
}