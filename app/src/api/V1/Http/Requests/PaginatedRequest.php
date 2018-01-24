<?php

namespace Api\V1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PaginatedRequest.
 *
 * @package Api\V1\Http\Requests
 */
class PaginatedRequest extends FormRequest
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
            'page' => ['filled', 'integer', 'min:1'],
            'per_page' => ['filled', 'integer', 'min:1', 'max:50'],
        ];
    }
}
