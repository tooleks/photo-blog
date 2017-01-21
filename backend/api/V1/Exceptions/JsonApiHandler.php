<?php

namespace Api\V1\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Debug\Exception\FlattenException;

/**
 * Class JsonApiHandler.
 *
 * @package Api\V1\Exceptions
 */
class JsonApiHandler extends ExceptionHandler
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
    ];

    /**
     * @inheritdoc
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * @inheritdoc
     */
    protected function prepareException(Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        } elseif ($e instanceof AuthenticationException) {
            $e = new HttpException(401, $e->getMessage());
        } elseif ($e instanceof AuthorizationException) {
            $e = new HttpException(403, $e->getMessage());
        } elseif ($e instanceof HttpException) {
            $e = new HttpException($e->getStatusCode(), trans("errors.http.{$e->getStatusCode()}"), null, $e->getHeaders());
        }

        return $e;
    }

    /**
     * @inheritdoc
     */
    public function render($request, Exception $e)
    {
        $e = $this->prepareException($e);

        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif ($e instanceof HttpException) {
            return $this->convertHttpExceptionToResponse($e);
        } elseif ($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        }

        return $this->getDefaultExceptionResponse();
    }

    /**
     * @inheritdoc
     */
    protected function convertHttpExceptionToResponse(Exception $e)
    {
        $e = FlattenException::create($e);

        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
        ], $e->getStatusCode(), $e->getHeaders());
    }

    /**
     * @inheritdoc
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
            'errors' => $e->validator->errors()->getMessages(),
        ], 422);
    }

    /**
     * Get default exception response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getDefaultExceptionResponse()
    {
        return response()->json([
            'status' => false,
            'message' => trans('errors.http.500'),
        ], 500);
    }
}
