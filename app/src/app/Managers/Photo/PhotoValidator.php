<?php

namespace App\Managers\Photo;

use function App\Util\validator_filter_attributes;
use App\Models\Tables\Constant;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * Class PhotoValidator.
 *
 * @package App\Managers\Photo
 */
class PhotoValidator
{
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
     * @param ValidatorFactory $validatorFactory
     * @param Config $config
     */
    public function __construct(ValidatorFactory $validatorFactory, Config $config)
    {
        $this->validatorFactory = $validatorFactory;
        $this->config = $config;
    }

    /**
     * @param array $attributes
     * @return array
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
        ];

        $this->validatorFactory->validate($attributes, $rules);

        return validator_filter_attributes($attributes, $rules);
    }
}
