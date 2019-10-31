<?php

namespace Askoldex\Teletant\Addons;

/**
 * Use next gen Keyboard addon - Menux
 * @deprecated
 */
class Keyboard
{
    const INLINE = 'inline_keyboard';
    const KEYBOARD = 'keyboard';

    private $out;
    private $type;

    public function __construct($type = self::KEYBOARD)
    {
        $this->type = $type;
    }

    public static function delete(bool $selective = false)
    {
        return json_encode(['remove_keyboard' => true, 'selective' => $selective]);
    }

    private function button($text, $t = false)
    {
        if ($t) return ['text' => $text, 'callback_data' => $text];
        return ['text' => $text];
    }

    public static function locationBtn($text)
    {
        return ['text' => $text, 'request_location' => true];
    }

    public static function contactBtn($text)
    {
        return ['text' => $text, 'request_contact' => true];
    }

    public static function urlBtn($text, $url)
    {
        return ['text' => $text, 'url' => $url];
    }

    public static function payBtn($text)
    {
        return ['text' => $text, 'pay' => true];
    }

    public static function btn($text, $data = null)
    {
        if (is_null($data)) $data = $text;
        return ['text' => $text, 'callback_data' => $data];
    }

    public function row()
    {
        $buttons = func_get_args();
        $row = [];
        foreach ($buttons as $button) {
            if (is_array($button)) {
                if ($this->type != self::INLINE AND isset($button['callback_data'])) unset($button['callback_data']);
                $row[] = $button;
            } else {
                if ($this->type == self::INLINE) $row[] = $this->button($button, true);
                else $row[] = $this->button($button);
            }
        }
        $this->out[$this->type][] = $row;
        return $this;
    }

    public function autoRows($buttons, $inLine)
    {
        $rows = array_chunk($buttons, $inLine);
        foreach ($rows as $row) {
            $tRow = [];
            foreach ($row as $button) {
                if (is_array($button)) {
                    if ($this->type != self::INLINE AND isset($button['callback_data'])) unset($button['callback_data']);
                    $tRow[] = $button;
                } else {
                    if ($this->type == self::INLINE) $tRow[] = $this->button($button, true);
                    else $tRow[] = $this->button($button);
                }
            }
            $this->out[$this->type][] = $tRow;
        }
    }

    public function arrayRow($buttons)
    {
        call_user_func_array([$this, 'row'], $buttons);
    }

    public function property($name, $value)
    {
        $this->out[$name] = $value;
        return $this;
    }

    public function build() {
        return json_encode($this->out);
    }

    public function getAsObject()
    {
        return $this->out;
    }

    public function __toString()
    {
        return $this->build();
    }
}