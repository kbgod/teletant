<?php

namespace Askoldex\Teletant\Entities\Inline;


use Askoldex\Teletant\Entities\Inline\Base\InlineQueryResult;

class Voice extends InlineQueryResult
{
    public function __construct($id)
    {
        $this->out['type'] = 'voice';
        $this->out['id'] = $id;
    }

    public function voiceFileID($voiceFileID)
    {
        $this->out['voice_file_id'] = $voiceFileID;
        return $this;
    }

    public function voiceUrl($voiceUrl)
    {
        $this->out['voice_url'] = $voiceUrl;
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

    public function voiceDuration($voiceDuration)
    {
        $this->out['voice_duration'] = $voiceDuration;
        return $this;
    }
}