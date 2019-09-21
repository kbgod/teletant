<?php

namespace Askoldex\Teletant\Entities\Inline;


use Askoldex\Teletant\Entities\Inline\Base\InlineQueryResult;

class Mpeg4Gif extends InlineQueryResult
{
    public function __construct($id)
    {
        $this->out['type'] = 'mpeg4_gif';
        $this->out['id'] = $id;
    }

    public function mpeg4Url($mpeg4Url)
    {
        $this->out['mpeg4_url'] = $mpeg4Url;
        return $this;
    }

    public function mpeg4FileID($mpeg4FileID)
    {
        $this->out['mpeg4_file_id'] = $mpeg4FileID;
        return $this;
    }

    public function mpeg4Width($mpeg4Width)
    {
        $this->out['mpeg4_width'] = $mpeg4Width;
        return $this;
    }

    public function mpeg4Height($mpeg4Height)
    {
        $this->out['mpeg4_height'] = $mpeg4Height;
        return $this;
    }

    public function mpeg4Duration($mpeg4Duration)
    {
        $this->out['mpeg4_duration'] = $mpeg4Duration;
        return $this;
    }

    public function thumbUrl($thumb_url)
    {
        $this->out['thumb_url'] = $thumb_url;
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
}