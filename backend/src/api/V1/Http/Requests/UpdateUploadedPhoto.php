<?php

namespace Api\V1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateUploadedPhoto.
 *
 * @package Api\V1\Http\Requests
 */
class UpdateUploadedPhoto extends FormRequest
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
            'path' => ['required', 'string', 'min:1', 'max:255'],
            'relative_url' => ['required', 'string', 'min:1', 'max:255'],
            'exif' => ['required', 'array'],
            'thumbnails' => ['required', 'array'],
            'thumbnails.*.width' => ['required', 'int', 'min:1'],
            'thumbnails.*.height' => ['required', 'int', 'min:1'],
            'thumbnails.*.path' => ['required', 'string', 'min:1', 'max:255'],
            'thumbnails.*.relative_url' => ['required', 'string', 'min:1', 'max:255'],
        ];
    }
}
