<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Audio extends Entity
{

    public function fileId(): ?string
    {
        return parent::_data('file_id');
    }

    public function fileUniqueId(): ?string
    {
        return parent::_data('file_unique_id');
    }

    public function duration(): ?int
    {
        return parent::_data('duration');

    }

    public function performer(): ?string
    {
        return parent::_data('performer');
    }

    public function title(): ?string
    {
        return parent::_data('title');
    }

    public function mimeType(): ?string
    {
        return parent::_data('mime_type');
    }

    public function fileSize(): ?int
    {
        return parent::_data('file_size');
    }

    public function thumb(): PhotoSize
    {
        return new PhotoSize(parent::_data('thumb'));
    }
}