<?php

namespace App\Exceptions;

use Exception;

class ImageValidationException extends Exception
{
    protected $code = 400;
}
