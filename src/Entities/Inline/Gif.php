<?php

namespace Askoldex\Teletant\Entities\Inline;


use Askoldex\Teletant\Entities\Inline\Base\InlineQueryResult;

class Gif extends InlineQueryResult
{
    public function __construct($id)
    {
        $this->out['type'] = 'gif';
        $this->out['id'] = $id;
    }

    public function gifUrl($GifUrl)
    {
        $this->out['gif_url'] = $GifUrl;
        return $this;
    }

    public function gifFileID($gifFileID)
    {
        $this->out['gif_file_id'] = $gifFileID;
        return $this;
    }

    public function gifWidth($gifWidth)
    {
        $this->out['gif_width'] = $gifWidth;
        return $this;
    }

    public function gifHeight($gifHeight)
    {
        $this->out['gif_height'] = $gifHeight;
        return $this;
    }

    public function gifDuration($gifDuration)
    {
        $this->out['gif_duration'] = $gifDuration;
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