<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\FormRequest;

/**
 * Class AuthenticateUserRequest
 * @package App\Http\Requests\Users
 */
class AuthenticateUserRequest extends FormRequest
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'email' => 'required|filled|email',
            'password' => 'required|filled',
        ];
    }
}
