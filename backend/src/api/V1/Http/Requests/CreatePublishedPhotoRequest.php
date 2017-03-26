<?php

namespace Api\V1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreatePublishedPhotoRequest.
 *
 * @package Api\V1\Http\Requests
 */
class CreatePublishedPhotoRequest extends FormRequest
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
            'photo_id' => ['required', 'integer'],
            'description' => ['required', 'string', 'min:1', 'max:65535'],
            'tags' => ['required', 'array'],
            'tags.*.text' => ['required', 'string', 'min:1', 'max:255'],
        ];
    }
}
