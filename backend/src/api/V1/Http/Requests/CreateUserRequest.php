<?php

namespace Api\V1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateUserRequest.
 *
 * @package Api\V1\Http\Requests
 */
class CreateUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users', 'min:1', 'max:255'],
            'password' => ['required', 'string', 'min:1', 'max:255'],
        ];
    }
}
