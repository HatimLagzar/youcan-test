<?php

namespace App\Validators;

use App\Exceptions\ImageValidationException;
use App\Exceptions\ValidationException;
use Illuminate\Support\Facades\Validator;

class ProductValidator
{
    /**
     * @throws ValidationException
     * @throws ImageValidationException
     */
    public function validate(array $inputs)
    {
        $this->validateInputs($inputs);
        $this->validateImage($inputs['image']);
    }

    /**
     * @param array $inputs
     * @throws ValidationException
     */
    private function validateInputs(array $inputs): void
    {
        $validation = Validator::make($inputs, $this->rules());

        if ($validation->fails()) {
            throw new ValidationException($validation->errors()->first());
        }
    }

    /**
     * @param $image
     * @throws ImageValidationException
     */
    private function validateImage($image): void
    {
        if (!is_string($image)) {
            $imageValidation = Validator::make(['image' => $image], $this->imageRules());

            if ($imageValidation->fails()) {
                throw new ImageValidationException($imageValidation->errors()->first());
            }
        }
    }

    private function rules(): array
    {
        return [
            'name' => 'string|required',
            'description' => 'string|required',
            'price' => 'numeric|required',
            'categories.*' => 'numeric|nullable',
            'image' => 'required',
        ];
    }

    private function imageRules(): array
    {
        return [
            'image' => 'image|max:10000|required',
        ];
    }
}
