<?php

namespace Askoldex\Teletant\Entities\Inline;


use Askoldex\Teletant\Entities\Inline\Base\InlineQueryResult;

class Article extends InlineQueryResult
{

    public function __construct($id)
    {
        $this->out['type'] = 'article';
        $this->out['id'] = $id;
    }

    public function title($title)
    {
        $this->out['title'] = $title;
        return $this;
    }

    public function url($url)
    {
        $this->out['url'] = $url;
        return $this;
    }

    public function hideUrl(bool $hide_url)
    {
        $this->out['hide_url'] = $hide_url;
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