<?php

namespace App\Managers\Subscription;

use App\Models\Tables\Constant;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use function App\Util\validator_filter_attributes;

/**
 * Class SubscriptionValidator.
 *
 * @package App\Managers\Subscription
 */
class SubscriptionValidator
{
    /**
     * @var ValidatorFactory
     */
    private $validatorFactory;

    /**
     * SubscriptionValidator constructor.
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
        $uniqueSubscriptionEmail = Rule::unique(Constant::TABLE_SUBSCRIPTIONS, 'email');

        $rules = [
            'email' => ['required', 'email', 'min:1', 'max:255', $uniqueSubscriptionEmail],
        ];

        $messages = [
            'email.unique' => trans('validation.model.subscription.email.unique'),
        ];

        $this->validatorFactory->validate($attributes, $rules, $messages);

        return validator_filter_attributes($attributes, $rules);
    }

    /**
     * @param array $filters
     * @return array
     * @throws ValidationException
     */
    public function validateForPaginate(array $filters): array
    {
        $allowedFilters = [
            'id',
            'email',
            'token',
        ];

        $this->validatorFactory->validate($filters, ['sort_attribute' => Rule::in($allowedFilters)]);

        return $filters;
    }
}
