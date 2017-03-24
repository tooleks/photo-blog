<?php

namespace Api\V1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateUploadedPhoto.
 *
 * @package Api\V1\Http\Requests
 */
class CreateUploadedPhoto extends FormRequest
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
            'path' => ['required', 'filled', 'string', 'min:1', 'max:255'],
            'relative_url' => ['required', 'filled', 'string', 'min:1', 'max:255'],
            'exif' => ['required', 'filled', 'array'],
            'thumbnails' => ['required', 'filled', 'array'],
            'thumbnails.*.width' => ['required', 'filled', 'int', 'min:1'],
            'thumbnails.*.height' => ['required', 'filled', 'int', 'min:1'],
            'thumbnails.*.path' => ['required', 'filled', 'string', 'min:1', 'max:255'],
            'thumbnails.*.relative_url' => ['required', 'filled', 'string', 'min:1', 'max:255'],
        ];
    }
}
