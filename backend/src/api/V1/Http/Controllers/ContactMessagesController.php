<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\ContactMessageRequest as ContactMessageRequest;
use Api\V1\Mail\ContactMessage;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class ContactMessagesController.
 *
 * @property Mailer mailer
 * @package Api\V1\Http\Controllers
 */
class ContactMessagesController extends Controller
{
    /**
     * ContactMessagesController constructor.
     *
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @apiVersion 1.0.0
     * @api {post} /api/v1/contact_message Create
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
        $this->mailer->send(new ContactMessage($request->all()));

        return new Response(null, Response::HTTP_CREATED);
    }
}
