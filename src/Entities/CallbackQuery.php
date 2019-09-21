<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class CallbackQuery extends Entity
{
    public function id(): ?int
    {
        return parent::_data('id');
    }

    public function from(): User
    {
        return new User(parent::_data('from'));
    }

    public function message(): Message
    {
        return new Message(parent::_data('message'));
    }

    public function inlineMessageId(): ?string
    {
        return parent::_data('inline_message_id');
    }

    public function chatInstance(): ?string
    {
        return parent::_data('chat_instance');
    }

    public function data(): ?string
    {
        return parent::_data('data');
    }

    public function gameShortName(): ?string
    {
        return parent::_data('game_short_name');
    }
}