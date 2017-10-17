<?php

namespace App\Managers\Photo;

use function App\Util\validator_filter_attributes;
use App\Models\Photo;
use App\Models\User;
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
     * @return array
     */
    private function getFileRules(): array
    {
        return [
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
    }

    /**
     * @param array $attributes
     * @return array
     */
    public function validateForCreateByFile(array $attributes): array
    {
        $validUserIdRule = Rule::exists((new User)->getTable(), 'id');

        $rules = array_merge($this->getFileRules(), [
            'created_by_user_id' => ['required', $validUserIdRule],
        ]);

        $this->validatorFactory->validate($attributes, $rules);

        return validator_filter_attributes($attributes, $rules);
    }

    /**
     * @param array $attributes
     * @return array
     */
    public function validateForSaveByFile(array $attributes): array
    {
        $rules = $this->getFileRules();

        $this->validatorFactory->validate($attributes, $rules);

        return validator_filter_attributes($attributes, $rules);
    }

    /**
     * @param Photo $photo
     * @param array $attributes
     * @return array
     */
    public function validateForSaveByAttributes(Photo $photo, array $attributes): array
    {
        $rules = [
            'description' => ['filled', 'string', 'min:1', 'max:65535'],
            'is_published' => ['filled', 'boolean'],
            'tags' => ['filled', 'array'],
            'tags.*.value' => ['filled', 'string', 'min:1', 'max:255'],
        ];

        if ($photo->isUnpublished()) {
            $rules['description'][] = 'required';
            $rules['tags'][] = 'required';
            $rules['tags.*.value'][] = 'required';
        }

        $this->validatorFactory->validate($attributes, $rules);

        return validator_filter_attributes($attributes, $rules);
    }
}
