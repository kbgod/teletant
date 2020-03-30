<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class PollAnswer extends Entity
{
    public function pollId(): ?string
    {
        return parent::_data('poll_id');
    }

    public function user(): User
    {
        return new User(parent::_data('user'));
    }

    /**
     * @return int[]|null
     */
    public function optionIds(): ?int
    {
        return parent::_data('option_ids');
    }
}