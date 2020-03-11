<?php


namespace Askoldex\Teletant\Interfaces;


interface ValidatorInterface
{
    /**
     * @param string $value
     * @return bool
     */
    public function validate(string $value): bool;

    /**
     * @return mixed Validation error message
     */
    public function error();

    /**
     * @return mixed finally variable value will be returned Context method var()
     */
    public function value();

    /**
     * @return bool
     */
    public function spaces(): bool;


}