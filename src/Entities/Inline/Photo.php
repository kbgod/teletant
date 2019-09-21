<?php

namespace Askoldex\Teletant\Entities\Inline;


use Askoldex\Teletant\Entities\Inline\Base\InlineQueryResult;

class Photo extends InlineQueryResult
{
    public function __construct($id)
    {
        $this->out['type'] = 'photo';
        $this->out['id'] = $id;
    }

    public function photoFileID($photoFileID)
    {
        $this->out['photo_file_id'] = $photoFileID;
        return $this;
    }

    public function photoUrl($photoUrl)
    {
        $this->out['photo_url'] = $photoUrl;
        return $this;
    }

    public function thumbUrl($thumb_url)
    {
        $this->out['thumb_url'] = $thumb_url;
        return $this;
    }

    public function photoWidth($photoWidth)
    {
        $this->out['photo_width'] = $photoWidth;
        return $this;
    }

    public function photoHeight($photoHeight)
    {
        $this->out['photo_height'] = $photoHeight;
        return $this;
    }

    public function title($title)
    {
        $this->out['title'] = $title;
        return $this;
    }

    public function description($description)
    {
        $this->out['description'] = $description;
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