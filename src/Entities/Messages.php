<?php


namespace Askoldex\Teletant\Entities;

use Askoldex\Teletant\Entities\Base\Entity;

class Messages extends Entity
{
    /**
     * @return Message[]
     */
    public function each()
    {
        $messages = [];
        if (is_array($this->_data())) {
            foreach ($this->_data() as $message) {
                $messages[] = new Message($message);
            }
            return $messages;
        } else return [];
    }
}