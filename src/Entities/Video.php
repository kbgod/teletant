<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Video extends Entity
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

    public function duration(): ?int
    {
        return parent::_data('duration');
    }

    public function thumb(): PhotoSize
    {
        return new PhotoSize(parent::_data('thumb'));
    }

    public function mimeType(): ?string
    {
        return parent::_data('mime_type');
    }

    public function fileSize(): ?int
    {
        return parent::_data('file_size');
    }
}