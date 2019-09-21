<?php

namespace Askoldex\Teletant\Entities\Inline;


use Askoldex\Teletant\Entities\Inline\Base\InlineQueryResult;

class Audio extends InlineQueryResult
{
    public function __construct($id)
    {
        $this->out['type'] = 'audio';
        $this->out['id'] = $id;
    }

    public function audioUrl($audioUrl)
    {
        $this->out['audio_url'] = $audioUrl;
        return $this;
    }

    public function audioFileID($audioFileID)
    {
        $this->out['audio_file_id'] = $audioFileID;
        return $this;
    }

    public function title($title)
    {
        $this->out['title'] = $title;
        return $this;
    }

    public function caption($caption)
    {
        $this->out['caption'] = $caption;
        return $this;
    }

    public function parseMode($parseMode)
    {
        $this->out['parse_mode'] = $parseMode;
        return $this;
    }

    public function performer($performer)
    {
        $this->out['performer'] = $performer;
        return $this;
    }

    public function audioDuration($audioDuration)
    {
        $this->out['audio_duration'] = $audioDuration;
        return $this;
    }
}