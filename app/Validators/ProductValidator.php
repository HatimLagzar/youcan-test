<?php

namespace App\Validators;

use App\Exceptions\ValidationException;
use Illuminate\Validation\Factory as Validator;

class ProductValidator
{
    private Validator $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validate the inputs and throw and exception if the validation fails
     * @throws ValidationException
     */
    public function validate(array $inputs)
    {
        $validation = $this->validator->make($inputs, $this->rules());

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }
    }

    private function rules(): array
    {
        return [
            'name' => 'string|required',
            'description' => 'string|required',
            'price' => 'numeric|required',
            'categories.*' => 'numeric|nullable',
            'image' => 'image|max:10000|required',
        ];
    }
}
