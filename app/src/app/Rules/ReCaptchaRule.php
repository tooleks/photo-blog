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
     * Get reCAPTCHA secret key.
     *
     * @return null|string
     */
    private static function getSecretKey(): ?string
    {
        return env('GOOGLE_RECAPTCHA_SECRET_KEY');
    }

    /**
     * Determine if reCAPTCHA is enabled.
     *
     * @return bool
     */
    public static function isEnabled(): bool
    {
        return (bool) static::getSecretKey();
    }

    /**
     * @inheritdoc
     */
    public function passes($attribute, $value)
    {
        if (!static::isEnabled()) {
            return true;
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query([
                    'secret' => static::getSecretKey(),
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
