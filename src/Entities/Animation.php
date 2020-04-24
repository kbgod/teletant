<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Animation extends Entity
{
    /**
     * @return string|null
     */
    public function fileId(): ?string
    {
        return parent::_data('file_id');
    }

    /**
     * @return string|null
     */
    public function fileUniqueId(): ?string
    {
        return parent::_data('file_unique_id');
    }

    /**
     * @return int|null
     */
    public function width(): ?int
    {
        return parent::_data('width');
    }

    /**
     * @return int|null
     */
    public function height(): ?int
    {
        return parent::_data('height');

    }

    /**
     * @return int|null
     */
    public function duration(): ?int
    {
        return parent::_data('duration');

    }

    /**
     * @return PhotoSize
     */
    public function thumb(): PhotoSize
    {
        return new PhotoSize(parent::_data('thumb'));
    }

    /**
     * @return string|null
     */
    public function fileName(): ?string
    {
        return parent::_data('file_name');

    }

    /**
     * @return string|null
     */
    public function mimeType(): ?string
    {
        return parent::_data('mime_type');

    }

    /**
     * @return int|null
     */
    public function fileSize(): ?int
    {
        return parent::_data('file_size');
    }
}