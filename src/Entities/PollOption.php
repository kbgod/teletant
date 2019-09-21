<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class PollOption extends Entity
{
    public function text(): ?string
    {
        return parent::_data('text');
    }

    public function voterCount(): ?int
    {
        return parent::_data('voter_count');
    }
}