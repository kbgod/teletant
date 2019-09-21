<?php
namespace Askoldex\Teletant\Addons;

use Askoldex\Teletant\Upload\InputFile;
use Askoldex\Teletant\Exception\TeletantException;

class MediaGroup
{

    private $MediaGroupArray = [];
    private $MediaGroupType;
    private $MediaGroupAttaches = [];

    const TYPE_VIDEO = 'video';
    const TYPE_PHOTO = 'photo';

    public function __construct($type)
    {
        $this->MediaGroupType = $type;
    }

    /**
     * @param $media
     * @param string $caption
     * @param integer $width
     * @param integer $height
     * @param integer $duration
     * @return $this
     * @throws TeletantException
     */
    public function add($media, $caption = '', $width = null, $height = null, $duration = null)
    {
        $MediaGroupNode = ['type' => $this->MediaGroupType, 'caption' => $caption];
        if (is_readable($media)) $MediaGroupNode['media'] = $this->attach($media);
        else $MediaGroupNode['media'] = $media;
        if ($this->MediaGroupType == self::TYPE_VIDEO) {
            $MediaGroupNode['width'] = $width;
            $MediaGroupNode['height'] = $height;
            $MediaGroupNode['duration'] = $duration;

        }
        $this->MediaGroupArray[] = $MediaGroupNode;
        return $this;
    }

    /**
     * @param $path
     * @return string
     * @throws TeletantException
     */
    private function attach($path)
    {
        $this->MediaGroupAttaches[basename($path)] = (new InputFile($path))->open();
        return 'attach://' . basename($path);
    }

    public function build(&$fields)
    {
        $fields['media'] = json_encode($this->MediaGroupArray);
        $fields = array_merge($fields, $this->MediaGroupAttaches);
    }

}