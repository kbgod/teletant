<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Updates extends Entity
{
    /**
     * @return Update[]
     */
    public function each()
    {
        $updates = [];
        if (is_array($this->_data())) {
            foreach ($this->_data() as $update) {
                $updates[] = new Update($update);
            }
            return $updates;
        } else return [];
    }
}