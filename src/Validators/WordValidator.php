<?php


namespace Askoldex\Teletant\Validators;


use Askoldex\Teletant\Interfaces\ValidatorInterface;

class WordValidator implements ValidatorInterface
{
    use PatternValidator;

    public function __construct()
    {
        $this->pattern = '[\w]+';
    }

    /**
     * @return bool
     */
    public function spaces(): bool
    {
        return false;
    }
}