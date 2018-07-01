<?php

namespace Api\V1\Http\Requests;

use App\Rules\ReCaptchaRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ReCaptchaRequest.
 *
 * @package Api\V1\Http\Requests
 */
class ReCaptchaRequest extends FormRequest
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
        $rules = [];

        $reCaptchaRule = $this->container->make(ReCaptchaRule::class);
        if ($reCaptchaRule->isEnabled()) {
            $rules['g_recaptcha_response'] = ['required', $reCaptchaRule];
        }

        return $rules;
    }
}
