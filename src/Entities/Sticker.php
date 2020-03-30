<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Sticker extends Entity
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

    public function isAnimated(): ?bool
    {
        return parent::_data('is_animated');
    }

    public function thumb(): PhotoSize
    {
        return new PhotoSize(parent::_data('thumb'));
    }

    public function emoji(): ?string
    {
        return parent::_data('emoji');
    }

    public function setName(): ?string
    {
        return parent::_data('set_name');
    }

    public function maskPosition(): MaskPosition
    {
        return new MaskPosition(parent::_data('mask_position'));
    }

    public function fileSize(): ?int
    {
        return parent::_data('file_size');
    }
}