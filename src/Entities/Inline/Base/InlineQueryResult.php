<?php

namespace Askoldex\Teletant\Entities\Inline\Base;


class InlineQueryResult extends Base
{
    public function inputMessageContent(InputMessageContent $input_message_content)
    {
        $this->out['input_message_content'] = $input_message_content->getAsObject();
        return $this;
    }

    public function keyboard(array $keyboard)
    {
        $this->out['reply_markup'] = $keyboard;
        return $this;
    }
}