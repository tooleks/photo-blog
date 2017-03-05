<?php

namespace Api\V1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class FindTags.
 *
 * @package Api\V1\Http\Requests
 */
class FindTags extends FormRequest
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
            'take' => ['required', 'filled', 'integer', 'min:1', 'max:100'],
            'skip' => ['required', 'filled', 'integer', 'min:0'],
        ];
    }
}
