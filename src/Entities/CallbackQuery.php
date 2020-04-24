<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class CallbackQuery extends Entity
{
    /**
     * @return int|null
     */
    public function id(): ?int
    {
        return parent::_data('id');
    }

    /**
     * @return User
     */
    public function from(): User
    {
        return new User(parent::_data('from'));
    }

    /**
     * @return Message
     */
    public function message(): Message
    {
        return new Message(parent::_data('message'));
    }

    /**
     * @return string|null
     */
    public function inlineMessageId(): ?string
    {
        return parent::_data('inline_message_id');
    }

    /**
     * @return string|null
     */
    public function chatInstance(): ?string
    {
        return parent::_data('chat_instance');
    }

    /**
     * @return string|null
     */
    public function data(): ?string
    {
        return parent::_data('data');
    }

    /**
     * @return string|null
     */
    public function gameShortName(): ?string
    {
        return parent::_data('game_short_name');
    }
}