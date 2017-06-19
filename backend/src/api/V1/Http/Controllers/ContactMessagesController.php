<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\ContactMessageRequest as ContactMessageRequest;
use Core\Mail\ContactMessage;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

/**
 * Class ContactMessagesController.
 *
 * @package Api\V1\Http\Controllers
 */
class ContactMessagesController extends Controller
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
     * @apiParam {String{1..255}} subject Message subject.
     * @apiParam {String{1..65535}} text Message text.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 201 Created
     * {}
     */

    /**
     * Create a resource.
     *
     * @param ContactMessageRequest $request
     * @return Response
     */
    public function create(ContactMessageRequest $request)
    {
        Mail::send(new ContactMessage($request->all()));

        return new Response(null, Response::HTTP_CREATED);
    }
}
