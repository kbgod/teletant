<?php

namespace Askoldex\Teletant\Entities\Inline;


use Askoldex\Teletant\Entities\Inline\Base\InlineQueryResult;

class Game extends InlineQueryResult
{
    public function __construct($id)
    {
        $this->out['type'] = 'game';
        $this->out['id'] = $id;
    }

    public function gameShortName($gameShortName)
    {
        $this->out['game_short_name'] = $gameShortName;
        return $this;
    }
}