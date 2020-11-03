<?php


namespace Askoldex\Teletant\Entities;


class Slots
{
    const BAR_ID = 0;
    const GRAPE_ID = 1;
    const LEMON_ID = 2;
    const SEVEN_ID = 3;

    const BAR_LABEL = 'bar';
    const GRAPE_LABEL = 'grape';
    const LEMON_LABEL = 'lemon';
    const SEVEN_LABEL = 'seven';


    const SLOTS = [
        self::BAR_ID => self::BAR_LABEL,
        self::GRAPE_ID => self::GRAPE_LABEL,
        self::LEMON_ID => self::LEMON_LABEL,
        self::SEVEN_ID => self::SEVEN_LABEL,
    ];

    protected $score;
    protected $slots;

    public function __construct(int $score)
    {
        $this->score = $score;

        $scr = $this->score - 1;
        $third = floor($scr / 16);
        $th16 = $third * 16;

        $second = floor(($scr - $th16) / 4);
        $first = floor($scr - $th16 - ($second * 4));

        $this->slots = compact('first', 'second', 'third');
        $this->slots = [
            'first' => self::SLOTS[$first],
            'second' => self::SLOTS[$second],
            'third' => self::SLOTS[$third],
        ];
    }

    public function getSlots()
    {
        return $this->slots;
    }

    public function isFirstTwoTheSame(): bool
    {
        return $this->slots['first'] == $this->slots['second'];
    }

    public function isAllTheSame(): bool
    {
        return $this->isFirstTwoTheSame() ? $this->slots['second'] == $this->slots['third'] : false;
    }
}