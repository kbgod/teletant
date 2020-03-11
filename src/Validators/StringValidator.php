<?php


namespace Askoldex\Teletant\Validators;


use Askoldex\Teletant\Interfaces\ValidatorInterface;

class StringValidator implements ValidatorInterface
{
    use PatternValidator;

    public function __construct()
    {
        $this->pattern = '[\w\s]+';
    }

    /**
     * @return bool
     */
    public function spaces(): bool
    {
        return true;
    }
}