<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\FormRequest;

/**
 * Class CreateUserRequest
 * @package App\Http\Requests\Users
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'name' => 'required|filled',
            'email' => 'required|filled|email',
            'password' => 'required|filled',
        ];
    }
}
