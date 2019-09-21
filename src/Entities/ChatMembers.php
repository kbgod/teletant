<?php


namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class ChatMembers extends Entity
{
    /**
     * @return ChatMember[]
     */
    public function each()
    {
        $members = [];
        if (is_array($this->_data())) {
            foreach ($this->_data() as $member) {
                $members[] = new ChatMember($member);
            }
            return $members;
        } else return [];
    }
}