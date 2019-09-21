<?php

namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class Game extends Entity
{
    public function title(): ?string
    {
        return parent::_data('title');
    }

    public function description(): ?string
    {
        return parent::_data('description');
    }

    public function photo(): ?array
    {
        return parent::_data('photo');
    }

    public function text(): ?string
    {
        return parent::_data('text');
    }

    /**
     * @return MessageEntity[]
     */
    public function text_entities(): array
    {
        $entities = parent::_data('text_entities');
        if (!is_null($entities)) {
            foreach ($entities as $key => $entity) {
                $entities[$key] = new MessageEntity($entity);
            }
            return $entities;
        } else return [];
    }

    public function animation(): Animation
    {
        return new Animation(parent::_data('animation'));
    }
}