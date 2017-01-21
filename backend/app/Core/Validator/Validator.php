<?php

namespace App\Core\Validator;

use Illuminate\Support\Facades\Validator as ValidatorFactory;
use Illuminate\Validation\ValidationException;

/**
 * Trait Validator
 * @package App\Core\Validator
 */
trait Validator
{
    /**
     * Get validation rules.
     *
     * @return array
     */
    protected function getValidationRules() : array
    {
        return [];
    }

    /**
     * Validate attributes.
     *
     * @param array $attributes
     * @param string $scenario
     * @return array
     * @throws ValidationException
     */
    protected function validate(array $attributes, string $scenario) : array
    {
        $rules = $this->getValidationRules()[$scenario] ?? [];

        $validator = ValidatorFactory::make($attributes, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return array_intersect_key($attributes, $rules);
    }
}
