<?php


namespace Askoldex\Teletant\Validators;


trait PatternValidator
{
    protected $pattern;

    protected $value;

    /**
     * @param string $value
     * @return bool
     */
    public function validate(string $value): bool
    {
        $this->value = $value;
        preg_match("#(" . $this->pattern . ")#", $value, $matches);
        return isset($matches[1]) ? ($value == $matches[1]) : false;
    }

    public function error()
    {
        return 'invalid_type';
    }

    /**
     * @return mixed finally variable value will be returned Context method var()
     */
    public function value()
    {
        return $this->value;
    }
}