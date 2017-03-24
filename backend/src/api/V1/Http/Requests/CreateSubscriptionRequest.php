<?php

namespace Api\V1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateSubscriptionRequest.
 *
 * @package Api\V1\Http\Requests
 */
class CreateSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email', 'unique:subscriptions', 'min:1', 'max:255'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function messages()
    {
        return [
            'email.unique' => trans('validation.model.subscription.email.unique'),
        ];
    }
}
