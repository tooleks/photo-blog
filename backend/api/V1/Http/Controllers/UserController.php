<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Resources\UserResource;

/**
 * Class UserController.
 *
 * @see UserResource
 * @package Api\V1\Http\Controllers
 */
class UserController extends ResourceController
{
    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/user Create
     * @apiName Create
     * @apiGroup User
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type application/json
     * @apiParam {String{1..255}} name User name.
     * @apiParam {String{1..255}} email User email address.
     * @apiParam {String{1..255}} password User password.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "name": "username",
     *          "email": "username@mail.address",
     *          "created_at": "2016-10-24 12:24:33",
     *          "updated_at": "2016-10-24 14:38:05",
     *          "role": [
     *              {
     *                  "name": "Customer"
     *              }
     *          ]
     *      }
     *  }
     */

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/user/:id Get
     * @apiName Get
     * @apiGroup User
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "name": "username",
     *          "email": "username@mail.address",
     *          "created_at": "2016-10-24 12:24:33",
     *          "updated_at": "2016-10-24 14:38:05",
     *          "role": [
     *              {
     *                  "name": "Customer"
     *              }
     *          ]
     *      }
     *  }
     */

    /**
     * @apiVersion 1.0.0
     * @api {put} /v1/user/:id Update
     * @apiName Update
     * @apiGroup User
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type application/json
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiParam {String{1..255}} name User name.
     * @apiParam {String{1..255}} email User email address.
     * @apiParam {String{1..255}} password User password.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "name": "username",
     *          "email": "username@mail.address",
     *          "created_at": "2016-10-24 12:24:33",
     *          "updated_at": "2016-10-24 14:38:05",
     *          "role": [
     *              {
     *                  "name": "Customer"
     *              }
     *          ]
     *      }
     *  }
     */

    /**
     * @apiVersion 1.0.0
     * @api {delete} /v1/user/:id Delete
     * @apiName Delete
     * @apiGroup User
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": 1
     *  }
     */
}
