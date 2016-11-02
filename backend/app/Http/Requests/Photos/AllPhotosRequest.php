<?php

namespace App\Http\Requests\Photos;

use App\Http\Requests\FormRequest;

/**
 * Class AllPhotosRequest
 * @package App\Http\Requests\Photos
 */
class AllPhotosRequest extends FormRequest
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
            'skip' => 'required|integer',
            'take' => 'required|integer',
        ];
    }
}
