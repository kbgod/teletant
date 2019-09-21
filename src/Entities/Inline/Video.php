<?php

namespace Askoldex\Teletant\Entities\Inline;


use Askoldex\Teletant\Entities\Inline\Base\InlineQueryResult;

class Video extends InlineQueryResult
{
    public function __construct($id)
    {
        $this->out['type'] = 'video';
        $this->out['id'] = $id;
    }

    public function videoFileID($videoFileID)
    {
        $this->out['video_file_id'] = $videoFileID;
        return $this;
    }

    public function videoUrl($videoUrl)
    {
        $this->out['video_url'] = $videoUrl;
        return $this;
    }

    public function mimeType($mimeType)
    {
        $this->out['mime_type'] = $mimeType;
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

    public function videoWidth($videoWidth)
    {
        $this->out['video_width'] = $videoWidth;
        return $this;
    }

    public function videoHeight($videoHeight)
    {
        $this->out['video_height'] = $videoHeight;
        return $this;
    }

    public function videoDuration($videoDuration)
    {
        $this->out['video_duration'] = $videoDuration;
        return $this;
    }

    public function description($description)
    {
        $this->out['description'] = $description;
        return $this;
    }
}