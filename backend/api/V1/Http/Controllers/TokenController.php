<?php

namespace Api\V1\Http\Controllers;

/**
 * Class TokenController.
 *
 * @see \Api\V1\Services\TokenService
 * @package Api\V1\Http\Controllers
 */
class TokenController extends ResourceController
{
    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/token Create
     * @apiName Create
     * @apiGroup Token
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParam {String{1..255}} email User's email address.
     * @apiParam {String{1..255}} password User's password.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "user_id": 1,
     *          "api_token": "user_api_token_string"
     *      }
     *  }
     *
     * @inheritdoc
     */
    public function create()
    {
        $token = $this->resource->create($this->request->all());

        $user = $token->getPresentee();

        // Set auth for further actions.
        $this->guard->setUser($user);

        return $token;
    }
}
