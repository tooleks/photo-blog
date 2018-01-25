<?php

namespace Api\V1\Http\Requests;

use App\Rules\ReCaptchaRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateAuthRequest.
 *
 * @package Api\V1\Http\Requests
 */
class CreateAuthRequest extends FormRequest
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
        $rules = [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];

        if (ReCaptchaRule::enabled()) {
            $rules['g_recaptcha_response'] = ['required', new ReCaptchaRule];
        }

        return $rules;
    }
}
