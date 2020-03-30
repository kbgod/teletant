<?php


namespace Askoldex\Teletant\Entities;


use Askoldex\Teletant\Entities\Base\Entity;

class StickerSet extends Entity
{
    public function name(): ?string
    {
        return parent::_data('name');
    }

    public function title(): ?string
    {
        return parent::_data('title');
    }

    public function isAnimated(): ?bool
    {
        return parent::_data('is_animated');
    }

    public function containsMasks(): ?bool
    {
        return parent::_data('contains_masks');
    }

    /**
     * @return Sticker[]
     */
    public function stickers()
    {
        $stickers = [];
        if (is_array($this->_data())) {
            foreach ($this->_data() as $sticker) {
                $stickers[] = new Sticker($sticker);
            }
            return $stickers;
        } else return [];
    }

    public function thumb(): PhotoSize
    {
        return new PhotoSize(parent::_data('thumb'));
    }
}