<?php


namespace Askoldex\Teletant\Validators;


use Askoldex\Teletant\Interfaces\ValidatorInterface;

class IntegerValidator implements ValidatorInterface
{
    use PatternValidator;

    public function __construct()
    {
        $this->pattern = '[\d]+';
    }

    /**
     * @return bool
     */
    public function spaces(): bool
    {
        return false;
    }
}