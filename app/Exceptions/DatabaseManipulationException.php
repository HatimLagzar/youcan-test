<?php

namespace App\Exceptions;

use Exception;

class DatabaseManipulationException extends Exception
{
    protected $code = 500;
}
