<?php
namespace App\Exceptions;

use Exception;

class UndefinedCurrencyException extends Exception
{
    protected $field;

    public function __construct(string $field, string $message = "", int $code = 422, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->field = $field;
    }

    public function getField()
    {
        return $this->field;
    }

}
