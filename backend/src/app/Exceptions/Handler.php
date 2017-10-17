<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;

/**
 * Class Handler.
 *
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * @inheritdoc
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        \League\OAuth2\Server\Exception\OAuthServerException::class,
    ];

    /**
     * @inheritdoc
     */
    protected function prepareException(Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = $this->modifyModelNotFoundException($e);
        } elseif ($e instanceof HttpException) {
            $e = $this->modifyHttpException($e);
        }

        $e = parent::prepareException($e);

        return $e;
    }

    /**
     * Convert exception message from "No query results for model [{$model}]" to "$model not found".
     *
     * @param ModelNotFoundException $e
     * @return Exception
     */
    private function modifyModelNotFoundException(ModelNotFoundException $e)
    {
        preg_match('(\[(.*?)\])', $e->getMessage(), $matches);

        $className = class_basename($matches[1] ?? null);

        if ($className) {
            $e = new NotFoundHttpException("$className not found", $e);
        }

        return $e;
    }

    /**
     * Convert empty exception message to valid HTTP status text.
     *
     * @param HttpException $e
     * @return HttpException
     */
    private function modifyHttpException(HttpException $e): HttpException
    {
        if (Str::length($e->getMessage()) === 0) {
            $e = new HttpException($e->getStatusCode(), Response::$statusTexts[$e->getStatusCode()] ?? null, $e);
        }

        return $e;
    }
}
