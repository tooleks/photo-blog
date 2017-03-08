<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\ContactMessage as ContactMessageRequest;
use Api\V1\Mail\ContactMessage;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

/**
 * Class ContactUsController.
 *
 * @package Api\V1\Http\Controllers
 */
class ContactMessageController extends Controller
{
    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/contact_message Create
     * @apiName Create
     * @apiGroup Contact Message
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParam {String{1..255}} email Author email address to reply.
     * @apiParam {String{1..255}} name Author name.
     * @apiParam {String{1..255}} subject Subject.
     * @apiParam {String{1..65535}} text Message text.
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": {
     *          "sent": 1
     *      }
     *  }
     */

    /**
     * Create a resource.
     *
     * @param ContactMessageRequest $request
     * @return array
     */
    public function create(ContactMessageRequest $request)
    {
        Mail::to(config('mail.address.administrator'))->send(new ContactMessage($request->all()));

        return ['sent' => 1];
    }
}
