<?php

namespace Askoldex\Teletant\Entities\Inline;


use Askoldex\Teletant\Entities\Inline\Base\InlineQueryResult;

class Document extends InlineQueryResult
{
    public function __construct($id)
    {
        $this->out['type'] = 'document';
        $this->out['id'] = $id;
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

    public function documentUrl($documentUrl)
    {
        $this->out['document_url'] = $documentUrl;
        return $this;
    }

    public function documentFileID($documentFileID)
    {
        $this->out['document_file_id'] = $documentFileID;
        return $this;
    }


    public function mimeType($mimeType)
    {
        $this->out['mime_type'] = $mimeType;
        return $this;
    }

    public function description($description)
    {
        $this->out['description'] = $description;
        return $this;
    }

    public function thumbUrl($thumb_url)
    {
        $this->out['thumb_url'] = $thumb_url;
        return $this;
    }

    public function thumbWidth($thumb_width)
    {
        $this->out['thumb_width'] = $thumb_width;
        return $this;
    }

    public function thumbHeight($thumb_height)
    {
        $this->out['thumb_height'] = $thumb_height;
        return $this;
    }
}