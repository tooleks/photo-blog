<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as CoreFormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class FormRequest
 * @package App\Http\Requests
 */
class FormRequest extends CoreFormRequest
{
    /**
     * @inheritdoc
     */
    protected function failedAuthorization()
    {
        throw new HttpException(403, trans('errors.http.403'));
    }
}
