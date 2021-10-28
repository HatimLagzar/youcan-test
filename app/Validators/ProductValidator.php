<?php

namespace App\Validators;

use Exception;
use Illuminate\Support\Facades\Validator;

class ProductValidator
{
    /**
     * @throws Exception
     */
    public static function validate(array $inputs)
    {
        $validation = Validator::make($inputs, [
            'name' => 'string|required',
            'description' => 'string|required',
            'price' => 'numeric|required',
            'categories.*' => 'numeric|nullable',
            'image' => 'required',
        ]);

        if ($validation->fails()) {
            throw new Exception($validation->errors()->first(), 400);
        }

        $image = $inputs['image'];
        if (!is_string($image)) {
            $imageValidation = Validator::make(['image' => $image], [
                'image' => 'image|max:10000|required',
            ]);

            if ($imageValidation->fails()) {
                throw new Exception($imageValidation->errors()->first(), 400);
            }
        }
    }
}
