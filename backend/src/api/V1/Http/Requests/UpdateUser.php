<?php

namespace Api\V1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateUser.
 *
 * @package Api\V1\Http\Requests
 */
class UpdateUser extends FormRequest
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
            'name' => ['filled', 'string', 'min:1', 'max:255'],
            'email' => [
                'filled',
                'string',
                'email',
                'min:1',
                'max:255',
            ],
            'password' => ['filled', 'string', 'min:1', 'max:255'],
        ];
    }
}
