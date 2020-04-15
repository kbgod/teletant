<?php


namespace Askoldex\Teletant\Addons;


use Askoldex\Teletant\Context;
use Askoldex\Teletant\Exception\MenuxException;
use Askoldex\Teletant\States\Scene;

class Menux
{
    const KEYBOARD = 'keyboard';
    const INLINE_KEYBOARD = 'inline_keyboard';

    private static $associations = [];
    /**
     * @var Menux[] $links
     */
    private static $links = [];

    private static $default = self::KEYBOARD;

    private static $defaultProperties = [
        'resize_keyboard' => true
    ];

    private $name;
    private $id;
    private $source = [];
    private $rowIndex = 0;
    private $firstRow = true;
    private $type = '';

    private function __construct($name, $id)
    {
        $this->name = $name;
        $this->id = $id;
        $this->type = self::$default;
        $this->source = self::$defaultProperties;
    }

    /**
     * @return $this
     */
    public function inline(): self
    {
        $this->type = self::INLINE_KEYBOARD;
        return $this;
    }

    /**
     * @return $this
     */
    public function default(): self
    {
        $this->type = self::KEYBOARD;
        return $this;
    }

    /**
     * @return Menux
     */
    public function reset(): self
    {
        $this->source[$this->type] = [];
        return $this;
    }

    /**
     * @param Menux $menu
     * @return Menux
     * @throws MenuxException
     */
    public function push(Menux $menu): self
    {
        if($this->type == $menu->type) {
            if(!array_key_exists($this->type, $this->source)) $this->source[$this->type] = [];
            if(!array_key_exists($menu->type, $menu->source)) $menu->source[$this->type] = [];
            $this->source[$this->type] = array_merge($this->source[$this->type], $menu->source[$menu->type]);
            return $this;
        } else {
            throw new MenuxException("'{$this->name}' type and '{$menu->name}' type, do not match");
        }
    }

    /**
     * @param bool $expression
     * @param Menux $menu
     * @return Menux
     * @throws MenuxException
     */
    public function pushIf(bool $expression, Menux $menu): self
    {
        if($expression) $this->push($menu);
        return $this;
    }

    /**
     * @param $button
     */
    private function addButton($button): void
    {
        if (is_array($button)) {
            if ($this->type == self::KEYBOARD and isset($button['callback_data'])) {
                unset($button['callback_data']);
            }
            $this->source[$this->type][$this->rowIndex][] = $button;
        } else {
            if ($this->type != self::KEYBOARD)
                $this->source[$this->type][$this->rowIndex][] = ['text' => $button, 'callback_data' => $button];
            else
                $this->source[$this->type][$this->rowIndex][] = ['text' => $button];
        }
    }

    /**
     * @param mixed ...$buttons
     * @return Menux
     */
    public function row(...$buttons): self
    {
        if ($this->firstRow) $this->firstRow = false;
        else $this->rowIndex++;

        if (count($buttons) > 0) {
            foreach ($buttons as $button) {
                $this->addButton($button);
            }
        }
        return $this;
    }

    /**
     * @param array $buttons
     * @return Menux
     */
    public function arrayRow(array $buttons): self
    {
        call_user_func_array([$this, 'row'], $buttons);
        return $this;
    }

    /**
     * @param array $buttons
     * @param int $inLine
     * @return Menux
     */
    public function autoRows(array $buttons, int $inLine): self
    {
        $rows = array_chunk($buttons, $inLine);
        foreach ($rows as $row) {
            $this->arrayRow($row);
        }
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return Menux
     */
    public function property(string $name, $value): self
    {
        $this->source[$name] = $value;
        return $this;
    }

    public function build() {

        $source = $this->source;
        if($this->isInlineKeyboard()) {
            $source = [self::INLINE_KEYBOARD => $source[self::INLINE_KEYBOARD]];
        }
        return json_encode($source);
    }

    public function isInlineKeyboard(): bool
    {
        return $this->type == self::INLINE_KEYBOARD;
    }

    public function isDefaultKeyboard(): bool
    {
        return $this->type == self::KEYBOARD;
    }


    public function getAsObject()
    {
        return $this->source;
    }

    public function __toString()
    {
        return $this->build();
    }

    /**
     * @param string $text
     * @param string|null $data
     * @return Menux
     */
    public function btn(string $text, string $data = null): self
    {
        $this->addButton(self::Button($text, $data));
        return $this;
    }

    /**
     * @param $text
     * @param $type
     * @return Menux
     */
    public function poll($text, $type = ''): self
    {
        $this->addButton(self::PollButton($text, $type));
        return $this;
    }

    public function quiz($text): self
    {
        return $this->poll($text, 'quiz');
    }

    /**
     * @param string $text
     * @return Menux
     */
    public function lBtn(string $text): self
    {
        $this->addButton(self::LocationButton($text));
        return $this;
    }

    /**
     * @param string $text
     * @return Menux
     */
    public function cBtn(string $text): self
    {
        $this->addButton(self::ContactButton($text));
        return $this;
    }

    /**
     * @param string $text
     * @param string $url
     * @return Menux
     */
    public function uBtn(string $text, string $url): self
    {
        $this->addButton(self::UrlButton($text, $url));
        return $this;
    }

    /**
     * @param string $text
     * @param Menux $menu
     * @return Menux
     */
    public function menu(string $text, Menux $menu): self
    {
        $this->addButton(self::MenuButton($text, $this, $menu));
        return $this;
    }

    /**
     * @param string $text
     * @param string|Scene $scene
     * @return Menux
     */
    public function scene(string $text, $scene): self
    {
        $this->addButton(self::SceneButton($text, $scene, $this));
        return $this;
    }

    /**
     * @var Menux[] $menus
     */
    private static $menus;
    private static $index = 0;

    /**
     * @param string $name
     * @param string|null $key
     * @return self
     * @throws MenuxException
     */
    public static function Create(string $name, string $key = null): self
    {
        if($key != null) {
            if(array_key_exists($key, self::$links))
                throw new MenuxException('Key "' . $key . '" already exists');
            self::$links[$key] = &self::$menus[self::$index];
        }
        return self::$menus[self::$index] = new self($name, self::$index++);
    }

    /**
     * @param string $key
     * @return Menux
     * @throws MenuxException
     */
    public static function Get(string $key): self
    {
        $menu = self::$links[$key];
        if($menu instanceof self)
            return self::$links[$key];
        else
            throw new MenuxException('Key "' . $key . '" is undefined');
    }

    /**
     * @param string|null $type
     */
    public static function DefaultType(string $type): void
    {
        self::$default = $type;
    }

    /**
     * @param array $properties
     */
    public static function DefaultProperties(array $properties): void
    {
        foreach ($properties as $property => $value) {
            self::$defaultProperties[$property] = $value;
        }
    }

    /**
     * @param string $text
     * @param string|null $data
     * @return array
     */
    public static function Button(string $text, string $data = null): array
    {
        if (is_null($data)) $data = $text;
        return ['text' => $text, 'callback_data' => $data];
    }

    /**
     * @param string $text
     * @param string $type
     * @return array
     */
    public static function PollButton(string $text, string $type): array
    {
        return ['text' => $text, 'request_poll' => compact('type')];
    }

    /**
     * @param string $text
     * @return array
     */
    public static function LocationButton(string $text): array
    {
        return ['text' => $text, 'request_location' => true];
    }

    /**
     * @param string $text
     * @return array
     */
    public static function ContactButton(string $text): array
    {
        return ['text' => $text, 'request_contact' => true];
    }

    /**
     * @param string $text
     * @param string $url
     * @return array
     */
    public static function UrlButton(string $text, string $url): array
    {
        return ['text' => $text, 'url' => $url];
    }

    /**
     * @param string $text
     * @return array
     */
    public static function PayButton(string $text): array
    {
        return ['text' => $text, 'pay' => true];
    }

    /**
     * @param string $text
     * @param Menux $fromMenu
     * @param Menux $toMenu
     * @return array
     */
    public static function MenuButton(string $text, Menux $fromMenu, Menux $toMenu): array
    {
        if($fromMenu->isDefaultKeyboard()) {
            self::$associations[$text] = [
                'type' => 'menus',
                'payload' => ['to' => $toMenu->id]
            ];
            return self::Button($text);
        } else return self::Button($text, 'menux/' . $toMenu->id . '/' . $fromMenu->id);
    }

    /**
     * @param string $text
     * @param string|Scene $scene
     * @param Menux|null $fromMenu
     * @return array
     */
    public static function SceneButton(string $text, $scene, Menux $fromMenu = null): array
    {
        $scene = $scene instanceof Scene ? $scene->getName() : $scene;
        if($fromMenu instanceof Menux) {
            if($fromMenu->isDefaultKeyboard()) {
                self::$associations[$text] = ['type' => 'scene', 'payload' => compact('scene')];
                return self::Button($text);
            }
        }
        return self::Button($text, 'menux_scene/' . $scene);
    }

    /**
     * @param bool $selective
     * @return string
     */
    public static function Delete(bool $selective = false): string
    {
        return json_encode(['remove_keyboard' => true, 'selective' => $selective]);
    }

    public static function Middleware()
    {
        return function (Context $ctx, $next) {
            if(!$ctx->callbackQuery()->isEmpty()) {
                $data = explode('/', $ctx->callbackQuery()->data(), 3);
                if(count($data) == 3 and $data[0] == 'menux') {
                    $toMenu = self::$menus[$data[1]];
                    $fromMenu = self::$menus[$data[2]];
                    if($fromMenu->isDefaultKeyboard()) {
                        $ctx->reply($toMenu->name, $toMenu);
                    } else {
                        if($toMenu->isDefaultKeyboard()) {
                            $ctx->reply($toMenu->name, $toMenu);
                        } else $ctx->editSelf($toMenu->name, $toMenu);
                    }
                    return true;
                }

                if(count($data) == 2 and $data[0] == 'menux_scene') {
                    $ctx->enter($data[1]);
                    return true;
                }
            }

            if(count(self::$associations) > 0 and $ctx->getText() != '') {
                if(isset(self::$associations[$ctx->getText()])) {
                    $association = self::$associations[$ctx->getText()];
                    switch ($association['type']) {
                        case 'menus':
                            $menus = $association['payload'];
                            $toMenu = self::$menus[$menus['to']];
                            $ctx->reply($toMenu->name, $toMenu);
                            break;

                        case 'scene':
                            var_dump($association);
                            $ctx->enter($association['payload']['scene']);
                            break;
                    }
                    return true;
                }
            }
            return $next($ctx);
        };
    }

    public static function dump()
    {
        var_dump(self::$menus, self::$index);
    }

}