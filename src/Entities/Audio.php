<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Audio extends Entity
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
    public function duration(): ?int
    {
        return parent::_data('duration');

    }

    /**
     * @return string|null
     */
    public function performer(): ?string
    {
        return parent::_data('performer');
    }

    /**
     * @return string|null
     */
    public function title(): ?string
    {
        return parent::_data('title');
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

    /**
     * @return PhotoSize
     */
    public function thumb(): PhotoSize
    {
        return new PhotoSize(parent::_data('thumb'));
    }
}