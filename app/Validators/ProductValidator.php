<?php

namespace App\Validators;

use App\Exceptions\ImageValidationException;
use App\Exceptions\ValidationException;
use Exception;
use Illuminate\Support\Facades\Validator;

class ProductValidator
{
    /**
     * @throws Exception
     */
    public static function validate(array $inputs)
    {
        self::validateInputs($inputs);
        self::validateImage($inputs['image']);
    }

    /**
     * @param array $inputs
     * @throws ValidationException
     */
    public static function validateInputs(array $inputs): void
    {
        $validation = Validator::make($inputs, [
            'name' => 'string|required',
            'description' => 'string|required',
            'price' => 'numeric|required',
            'categories.*' => 'numeric|nullable',
            'image' => 'required',
        ]);

        if ($validation->fails()) {
            throw new ValidationException($validation->errors()->first());
        }
    }

    /**
     * @param $image
     * @throws ImageValidationException
     */
    public static function validateImage($image): void
    {
        if (!is_string($image)) {
            $imageValidation = Validator::make(['image' => $image], [
                'image' => 'image|max:10000|required',
            ]);

            if ($imageValidation->fails()) {
                throw new ImageValidationException($imageValidation->errors()->first());
            }
        }
    }
}
