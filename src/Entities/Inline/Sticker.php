<?php

namespace Askoldex\Teletant\Entities\Inline;


use Askoldex\Teletant\Entities\Inline\Base\InlineQueryResult;

class Sticker extends InlineQueryResult
{
    public function __construct($id)
    {
        $this->out['type'] = 'sticker';
        $this->out['id'] = $id;
    }

    public function stickerFileID($stickerFileID)
    {
        $this->out['voice_file_id'] = $stickerFileID;
        return $this;
    }
}