<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class PassportFile extends Entity
{
    public function fileId(): ?string
    {
        return parent::_data('file_id');
    }

    public function fileUniqueId(): ?string
    {
        return parent::_data('file_unique_id');
    }

    public function fileSize(): ?int
    {
        return parent::_data('file_size');
    }

    public function fileDate(): ?int
    {
        return parent::_data('file_date');
    }
}