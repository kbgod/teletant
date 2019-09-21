<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Poll extends Entity
{
    public function id(): ?string
    {
        return parent::_data('id');
    }

    public function question(): ?string
    {
        return parent::_data('question');
    }

    /**
     * @return PollOption[]
     */
    public function options(): array
    {
        $options = parent::_data('options');
        if (!is_null($options)) {
            foreach ($options as $key => $option) {
                $options[$key] = new PollOption($option);
            }
            return $options;
        } else return [];
    }

    public function isClosed(): ?bool
    {
        return parent::_data('is_closed');
    }
}