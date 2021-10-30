<?php

namespace App\Exceptions;

use Exception;

class UploadExternalFileException extends Exception
{
    protected $code = 400;

    public function __construct(string $message)
    {
        parent::__construct($message, $this->code);
    }
}
