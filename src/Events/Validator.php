<?php


namespace Askoldex\Teletant\Events;


use Askoldex\Teletant\Exception\ValidatorException;

trait Validator
{
    protected $types = [
        'integer' => ['pattern' => '[\d]+', 'spaces' => false],
        'float' => ['pattern' => '-?\d+(\.\d+)?', 'spaces' => false],
        'string' => ['pattern' => '[\w\s]+', 'spaces' => true],
        'word' => ['pattern' => '[\w]+', 'spaces' => false],
        'any' => ['pattern' => '(.*?)', 'spaces' => false],
        'char' => ['pattern' => '[\w]', 'spaces' => false],
    ];

    /**
     * @param string $type
     * @param string $pattern
     * @return $this
     */
    public function addValidationType(string $type, string $pattern)
    {
        $this->types[$type] = $pattern;
        return $this;
    }

    /**
     * @param array $types
     * @return $this
     */
    public function addValidationTypes(array $types)
    {
        if (count($types) > 0) {
            foreach ($types as $type => $pattern)
                $this->addValidationType($type, $pattern);
        }
        return $this;
    }

    /**
     * @param string $type
     * @param string $value
     * @return bool
     */
    protected function validateType(string $type, string $value)
    {
        preg_match("#(" . $this->types[$type]['pattern'] . ")#", $value, $matches);
        return isset($matches[1]) ? ($value == $matches[1]) : false;
    }

    /**
     * @param array $parsedVariables
     * @param array $variables
     * @return array
     */
    protected function validateVariables(array $parsedVariables, array $variables)
    {
        $output = [];
        $output['variables'] = [];
        $output['errors'] = [];
        foreach ($variables as $varInfo) {
            if($varInfo['box'] != null) {
                $boxLen = mb_strlen($varInfo['box']);
                $varValue = mb_substr($parsedVariables[$varInfo['name']], $boxLen, -$boxLen);
            } else {
                $varValue = $parsedVariables[$varInfo['name']];
            }
            $output['variables'][$varInfo['name']] = $varValue;
            if($varInfo['required'] == true and $varValue == null) {
                $output['errors'][$varInfo['name']] = 'not_specified';
            } else {
                if ($varInfo['type'] != 'any') {
                    if ($varValue != null and !$this->validateType($varInfo['type'], $varValue)) {
                        $output['errors'][$varInfo['name']] = 'invalid_type';
                    }
                }
            }
        }
        return $output;
    }

    /**
     * @param string $pattern
     * @param bool $default
     * @return string
     * @throws ValidatorException
     */
    protected function makeRegex(string $pattern, bool $default = true)
    {
        preg_match_all("#{(.*?)}#", $pattern, $matches);
        $matches = $matches[1];
        foreach ($matches as $match) {
            list($variable, $type, $box) = explode(':', $match, 3);
            //$type = $type == null ? 'any' : $type;
            if($type != null) {
                if(array_key_exists($type, $this->types)) {
                    $spaces = $this->types[$type]['spaces'];
                } else {
                    throw new ValidatorException('Undefined validation type "'.$type.'"');
                }
            } else {
                $spaces = false;
            }
            $spaces = $spaces ? '' : '?';
            $pattern = str_replace('{' . $match . '}', '(?<' . $variable . '>'.$box.'(.*'.$spaces.')'.$box.')', $pattern);
        }
        return $default ? '#^' .$pattern. '$#ui' : $pattern;
    }

    protected function parseField($field)
    {
        preg_match_all("#{(.*?)}#", $field, $matches);
        $matches = $matches[1];
        $vars = [];
        if(count($matches) == 0) {
            $output['variables'] = [];
            $output['patterns'] = [$field];
            return $output;
        }
        foreach ($matches as $index => $match) {
            $position = mb_strpos($field, '{' . $match . '}');
            $patternLen = $position + strlen('{' . $match . '}');
            $isRequired = mb_substr($match, -1) == '?' ? false : true;
            $len = $isRequired ? mb_strlen($match) : mb_strlen($match) - 1;
            list($variable, $type, $box) = explode(':', mb_substr($match, 0, $len), 3);
            $type = $type == null ? 'any' : $type;
            $vars[] = [
                'name' => $variable,
                'type' => $type,
                'box' => $box,
                'required' => $isRequired,
                'len' => $patternLen,
                'position' => $position,
                'index' => $index

            ];
        }
        $rVars = array_reverse($vars);
        $output['variables'] = $vars;
        $output['patterns'] = [];
        $r = false;
        $skip = false;
        foreach ($rVars as $rIndex => $rVar) {
            if($skip) {
                $skip = false;
                continue;
            }
            $box = $rVar['box'] == null ? '' : ':'.$rVar['box'];
            $type = $rVar['type'] == 'any' ? '' : ':'.$rVar['type'];
            if($r or $rVar['required']) { // Если за переменной будут необ перем, то они станут обяз
                $r = true;
                $pattern = mb_substr($field, 0, $rVar['position']) . "{{$rVar['name']}{$type}{$box}}";
                $pattern = preg_replace("#{(.*?)\?}#", '{$1}', $pattern);
                $output['patterns'][] = $pattern;
                if($rVar['index'] > 0) break;
            } else {
                $pattern = mb_substr($field, 0, $rVar['position']) . "{{$rVar['name']}{$type}$box}";
                $pattern = preg_replace("#{(.*?)\?}#", '{$1}', $pattern);
                $output['patterns'][] = $pattern;
                $pattern = trim(mb_substr($field, 0, $rVar['position']));
                $pattern = preg_replace("#{(.*?)\?}#", '{$1}', $pattern);
                $output['patterns'][] = $pattern;
                $skip = true;

                if(array_key_exists($rIndex + 1, $rVars)) {
                    $next = $rVars[$rIndex + 1];
                    if($next['required']) {
                        $r = true;
                        break;
                    }
                }
            }
        }
        if($r) {
            $pattern = trim(mb_substr($field, 0, $vars[0]['position'])) . '(.*)';
            $output['patterns'][] = $pattern;
        }

        return $output;
    }
}