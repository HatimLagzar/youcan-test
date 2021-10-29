<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\MessageBag;

class ValidationException extends Exception
{
    protected $code = 400;
    private Validator $validator;

    public function __construct(Validator $validator)
    {
        parent::__construct($validator->errors()->first(), $this->code);
        $this->validator = $validator;
    }

    public function errors(): MessageBag
    {
        return $this->validator->errors();
    }
}
