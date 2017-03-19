<?php

namespace Api\V1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ContactMessage.
 *
 * @package Api\V1\Http\Requests
 */
class ContactMessage extends FormRequest
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
            'email' => ['required', 'filled', 'email', 'min:1', 'max:255'],
            'name' => ['required', 'filled', 'string', 'min:1', 'max:255'],
            'subject' => ['required', 'filled', 'string', 'min:1', 'max:255'],
            'text' => ['required', 'filled', 'string', 'min:1', 'max:65535'],
        ];
    }
}