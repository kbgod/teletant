<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class ChosenInlineResult extends Entity
{
    public function resultId(): ?string
    {
        return parent::_data('result_id');
    }

    public function from(): User
    {
        return new User(parent::_data('from'));
    }

    public function location(): Location
    {
        return new Location(parent::_data('location'));
    }

    public function inlineMessageId(): ?string
    {
        return parent::_data('inline_message_id');
    }

    public function query(): ?string
    {
        return parent::_data('query');
    }
}