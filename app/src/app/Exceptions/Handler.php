<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException as EloquentModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException as SymfonyHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as SymfonyNotFoundHttpException;

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
        if ($e instanceof EloquentModelNotFoundException) {
            $e = $this->modifyEloquentModelNotFoundException($e);
        }

        $e = parent::prepareException($e);

        if ($e instanceof SymfonyHttpException) {
            $e = $this->modifySymfonyHttpException($e);
        }

        return $e;
    }

    /**
     * @param EloquentModelNotFoundException $e
     * @return Exception
     */
    private function modifyEloquentModelNotFoundException(EloquentModelNotFoundException $e)
    {
        preg_match('(\[(.*?)\])', $e->getMessage(), $matches);

        $className = class_basename($matches[1] ?? null);

        if ($className) {
            $e = new SymfonyNotFoundHttpException(__('errors.not-found', ['model' => $className]), $e);
        }

        return $e;
    }

    /**
     * @param SymfonyHttpException $e
     * @return SymfonyHttpException
     */
    private function modifySymfonyHttpException(SymfonyHttpException $e): SymfonyHttpException
    {
        if (Str::length($e->getMessage()) === 0) {
            $statusCode = $e->getStatusCode();
            $message = Response::$statusTexts[$statusCode] ?? null;
            $e = new SymfonyHttpException($statusCode, $message, $e);
        }

        return $e;
    }
}
