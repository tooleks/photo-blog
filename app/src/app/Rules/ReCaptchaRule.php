<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class ReCaptchaRule.
 *
 * @package App\Rules
 */
class ReCaptchaRule implements Rule
{
    /**
     * @inheritdoc
     */
    public function passes($attribute, $value)
    {
        $reCaptchaSecretKey = env('RECAPTCHA_SECRET_KEY');

        // Note: If reCAPTCHA secret key is not configured consider all value as valid.
        if (is_null($reCaptchaSecretKey)) {
            return true;
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query([
                    'secret' => $reCaptchaSecretKey,
                    'response' => $value,
                ]),
            ],
        ]);

        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $result = json_decode($response);

        return $result->success;
    }

    /**
     * @inheritdoc
     */
    public function message()
    {
        return __('validation.recaptcha');
    }
}
