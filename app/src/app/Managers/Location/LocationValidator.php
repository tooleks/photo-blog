<?php

namespace App\Managers\Location;

use App\Rules\LatitudeRule;
use App\Rules\LongitudeRule;
use Illuminate\Contracts\Container\Container;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\ValidationException;
use function App\Util\validator_filter_attributes;

/**
 * Class LocationValidator.
 *
 * @package App\Managers\Location
 */
class LocationValidator
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var ValidatorFactory
     */
    private $validatorFactory;

    /**
     * LocationValidator constructor.
     *
     * @param Container $container
     * @param ValidatorFactory $validatorFactory
     */
    public function __construct(Container $container, ValidatorFactory $validatorFactory)
    {
        $this->container = $container;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $attributes
     * @return array
     * @throws ValidationException
     */
    public function validateForCreate(array $attributes): array
    {
        $rules = [
            'latitude' => ['required', $this->container->make(LatitudeRule::class)],
            'longitude' => ['required', $this->container->make(LongitudeRule::class)],
        ];

        $this->validatorFactory->validate($attributes, $rules);

        return validator_filter_attributes($attributes, $rules);
    }
}
