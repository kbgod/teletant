<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class ChatPhoto extends Entity
{

    public function smallFileId(): ?string
    {
        return parent::_data('small_file_id');
    }

    public function smallFileUniqueId(): ?string
    {
        return parent::_data('small_file_unique_id');
    }

    public function bigFileId(): ?string
    {
        return parent::_data('big_file_id');
    }

    public function bigFileUniqueId(): ?string
    {
        return parent::_data('big_file_unique_id');
    }
}