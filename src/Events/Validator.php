<?php


namespace Askoldex\Teletant\Events;


use Askoldex\Teletant\Exception\ValidatorException;
use Askoldex\Teletant\Interfaces\ValidatorInterface;
use Askoldex\Teletant\Validators\AnyValidator;
use Askoldex\Teletant\Validators\CharValidator;
use Askoldex\Teletant\Validators\FloatValidator;
use Askoldex\Teletant\Validators\IntegerValidator;
use Askoldex\Teletant\Validators\StringValidator;
use Askoldex\Teletant\Validators\WordValidator;

trait Validator
{
    protected $validators = [
        'integer' => IntegerValidator::class,
        'float' => FloatValidator::class,
        'string' => StringValidator::class,
        'word' => WordValidator::class,
        'any' => AnyValidator::class,
        'char' => CharValidator::class,
    ];


    /**
     * @param string $name
     * @return ValidatorInterface
     */
    private function getValidator(string $name): ValidatorInterface
    {
        return new $this->validators[$name];
    }

    /**
     * @param string $name
     * @param string $validator
     * @return $this
     * @throws ValidatorException
     */
    public function addValidator(string $name, string $validator)
    {
        if($name == 'any') {
            throw new ValidatorException('Validation name "any" cannot be redeclared!');
        }
        if(in_array(ValidatorInterface::class, class_implements($validator))) {
            $this->validators[$name] = $validator;
        } else {
            throw new ValidatorException("Wrong validator [{$validator}]. Validator must be implements ValidatorInterface!");
        }
        return $this;
    }

    /**
     * @param array $validators
     * @return $this
     * @throws ValidatorException
     */
    public function addValidators(array $validators)
    {
        if (count($validators) > 0) {
            foreach ($validators as $name => $validator)
                $this->addValidator($name, $validator);
        }
        return $this;
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
                $varValue = $parsedVariables[$varInfo['name']] ?? null;
            }
            $output['variables'][$varInfo['name']] = $varValue;
            if($varInfo['required'] == true and $varValue == null) {
                $output['errors'][$varInfo['name']] = 'not_specified';
            } else {
                if ($varInfo['type'] != 'any') {
                    $validator = $this->getValidator($varInfo['type']);
                    if ($varValue != null and !$validator->validate($varValue)) {
                        $output['errors'][$varInfo['name']] = $validator->error();
                    } else {
                        $output['variables'][$varInfo['name']] = $validator->value();
                    }
                    unset($validator);
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
            $variableParameters = explode(':', $match, 3);
            $variable = $variableParameters[0] ?? null;
            $type = $variableParameters[1] ?? null;
            $box = $variableParameters[2] ?? null;
            $type = $type == null ? 'any' : $type;
            if($type != null) {
                if(array_key_exists($type, $this->validators)) {
                    $validator = $this->getValidator($type);
                    $spaces = $validator->spaces();
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

    /**
     * @param $field
     * @return mixed
     * @throws ValidatorException
     */
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
            $variableParameters = explode(':', mb_substr($match, 0, $len), 3);
            $variable = $variableParameters[0] ?? null;
            if($variable == null) {
                throw new ValidatorException('Variable name cannot be empty. Variable position: ' . $index);
            }
            $type = $variableParameters[1] ?? null;
            $box = $variableParameters[2] ?? null;
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