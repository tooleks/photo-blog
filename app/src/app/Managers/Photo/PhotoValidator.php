<?php

namespace App\Managers\Photo;

use App\Models\Tables\Constant;
use App\Rules\LatitudeRule;
use App\Rules\LongitudeRule;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Container\Container;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use function App\Util\validator_filter_attributes;

/**
 * Class PhotoValidator.
 *
 * @package App\Managers\Photo
 */
class PhotoValidator
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
     * @var Config
     */
    private $config;

    /**
     * PhotoValidator constructor.
     *
     * @param Container $container
     * @param ValidatorFactory $validatorFactory
     * @param Config $config
     */
    public function __construct(Container $container, ValidatorFactory $validatorFactory, Config $config)
    {
        $this->container = $container;
        $this->validatorFactory = $validatorFactory;
        $this->config = $config;
    }

    /**
     * @param array $attributes
     * @return array
     * @throws ValidationException
     */
    public function validateForCreate(array $attributes): array
    {
        $validUserIdRule = Rule::exists(Constant::TABLE_USERS, 'id');

        $rules = [
            'created_by_user_id' => ['required', $validUserIdRule],
            'file' => [
                'required',
                'file',
                'image',
                'mimes:jpeg',
                sprintf('dimensions:min_width=%s,min_height=%s',
                    $this->config->get('main.upload.min-image-width'),
                    $this->config->get('main.upload.min-image-height')),
                sprintf('min:%s', $this->config->get('main.upload.min-size')),
                sprintf('max:%s', $this->config->get('main.upload.max-size')),
            ],
            'location' => [],
            'location.latitude' => ['required_with:location', 'numeric', $this->container->make(LatitudeRule::class)],
            'location.longitude' => ['required_with:location', 'numeric', $this->container->make(LongitudeRule::class)],
        ];

        $this->validatorFactory->validate($attributes, $rules);

        return validator_filter_attributes($attributes, $rules);
    }

    /**
     * @param array $attributes
     * @return array
     * @throws ValidationException
     */
    public function validateForUpdate(array $attributes): array
    {
        $rules = [
            'location' => [],
            'location.latitude' => ['required', $this->container->make(LatitudeRule::class)],
            'location.longitude' => ['required', $this->container->make(LongitudeRule::class)],
        ];

        $this->validatorFactory->validate($attributes, $rules);

        return validator_filter_attributes($attributes, $rules);
    }
}
