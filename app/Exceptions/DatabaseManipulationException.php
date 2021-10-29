<?php

namespace App\Exceptions;

use Exception;

class DatabaseManipulationException extends Exception
{
    protected $code = 500;

    public function __construct(string $message)
    {
        parent::__construct($message, $this->code);
    }
}
