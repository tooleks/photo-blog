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
     * @var string
     */
    private $apiEndpoint = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * @var string
     */
    private $secretKey;

    /**
     * ReCaptchaRule constructor.
     *
     * @param null|string $secretKey
     */
    public function __construct(?string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * Determine if reCAPTCHA is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) $this->secretKey;
    }

    /**
     * @inheritdoc
     */
    public function passes($attribute, $value)
    {
        if (!$this->isEnabled()) {
            return true;
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query([
                    'secret' => $this->secretKey,
                    'response' => $value,
                ]),
            ],
        ]);

        $response = file_get_contents($this->apiEndpoint, false, $context);
        $result = json_decode($response);

        return optional($result)->success;
    }

    /**
     * @inheritdoc
     */
    public function message()
    {
        return __('validation.recaptcha');
    }
}
