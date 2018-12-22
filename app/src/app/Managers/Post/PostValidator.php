<?php

namespace App\Managers\Post;

use App\Models\Post;
use App\Models\Tables\Constant;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use function App\Util\validator_filter_attributes;

/**
 * Class PostValidator.
 *
 * @package App\Managers\Post
 */
class PostValidator
{
    /**
     * @var ValidatorFactory
     */
    private $validatorFactory;

    /**
     * PostValidator constructor.
     *
     * @param ValidatorFactory $validatorFactory
     */
    public function __construct(ValidatorFactory $validatorFactory)
    {
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param array $attributes
     * @return array
     * @throws ValidationException
     */
    public function validateForCreate(array $attributes): array
    {
        $validUserIdRule = Rule::exists(Constant::TABLE_USERS, 'id');
        $validPhotoIdRule = Rule::exists(Constant::TABLE_PHOTOS, 'id');
        $uniquePhotoIdRule = Rule::unique(Constant::TABLE_POSTS_PHOTOS, 'photo_id');

        $rules = [
            'is_published' => ['required', 'boolean'],
            'created_by_user_id' => ['required', $validUserIdRule],
            'description' => ['required', 'string', 'max:1000'],
            'photo.id' => ['required', $validPhotoIdRule, $uniquePhotoIdRule],
            'tags' => ['required', 'array'],
            'tags.*.value' => ['required', 'string', 'max:255'],
        ];

        $this->validatorFactory->validate($attributes, $rules);

        return validator_filter_attributes($attributes, $rules);
    }

    /**
     * @param Post $post
     * @param array $attributes
     * @return array
     * @throws ValidationException
     */
    public function validateForUpdate(Post $post, array $attributes): array
    {
        $validPhotoIdRule = Rule::exists(Constant::TABLE_PHOTOS, 'id');
        $uniquePhotoIdRule = Rule::unique(Constant::TABLE_POSTS_PHOTOS, 'photo_id')->ignore(optional($post->photo)->id, 'photo_id');

        $rules = [
            'is_published' => ['filled', 'boolean'],
            'description' => ['filled', 'string', 'max:1000'],
            'photo.id' => ['filled', $validPhotoIdRule, $uniquePhotoIdRule],
            'tags' => ['filled', 'array'],
            'tags.*.value' => ['required', 'string', 'max:255'],
        ];

        $this->validatorFactory->validate($attributes, $rules);

        return validator_filter_attributes($attributes, $rules);
    }

    /**
     * @param array $filters
     * @return array
     * @throws ValidationException
     */
    public function validateForFiltering(array $filters): array
    {
        $rules = [
            'search_phrase' => ['string', 'max:50'],
            'tag' => ['string', 'max:50'],
        ];

        $this->validatorFactory->validate($filters, $rules);

        return validator_filter_attributes($filters, $rules);
    }
}
