<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class File extends Entity
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

    public function filePath(): ?string
    {
        return parent::_data('file_path');
    }
}