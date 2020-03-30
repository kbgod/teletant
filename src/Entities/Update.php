<?php

namespace Askoldex\Teletant\Entities;

use Askoldex\Teletant\Entities\Base\Entity;

class Update extends Entity
{
    public function updateId(): ?int
    {
        return parent::_data('update_id');
    }

    public function message(): Message
    {
        return new Message(parent::_data('message'));
    }

    public function editedMessage(): Message
    {
        return new Message(parent::_data('edited_message'));
    }

    public function channelPost(): Message
    {
        return new Message(parent::_data('channel_post'));
    }

    public function editedChannelPost(): Message
    {
        return new Message(parent::_data('edited_channel_post'));
    }

    public function inlineQuery(): InlineQuery
    {
        return new InlineQuery(parent::_data('inline_query'));
    }

    public function chosenInlineResult(): ChosenInlineResult
    {
        return new ChosenInlineResult(parent::_data('chosen_inline_result'));
    }

    public function callbackQuery(): CallbackQuery
    {
        return new CallbackQuery(parent::_data('callback_query'));
    }

    public function shippingQuery(): ShippingQuery
    {
        return new ShippingQuery(parent::_data('shipping_query'));
    }

    public function preCheckoutQuery(): PreCheckoutQuery
    {
        return new PreCheckoutQuery(parent::_data('pre_checkout_query'));
    }

    public function poll(): Poll
    {
        return new Poll(parent::_data('poll'));
    }

    public function pollAnswer(): PollAnswer
    {
        return new PollAnswer(parent::_data('poll_answer'));
    }

}