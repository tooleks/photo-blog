<?php

namespace Api\V1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateToken.
 *
 * @package Api\V1\Http\Requests
 */
class CreateToken extends FormRequest
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
            'email' => ['required', 'email', 'min:1', 'max:255'],
            'password' => ['required', 'min:1', 'max:255'],
        ];
    }
}
