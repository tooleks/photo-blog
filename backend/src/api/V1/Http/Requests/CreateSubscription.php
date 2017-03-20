<?php

namespace Api\V1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateSubscription.
 *
 * @package Api\V1\Http\Requests
 */
class CreateSubscription extends FormRequest
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
            'email' => ['required', 'filled', 'string', 'email', 'unique:subscriptions', 'min:1', 'max:255'],
        ];
    }
}
