<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\ContactMessageRequest as ContactMessageRequest;
use App\Mail\ContactMessage;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
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
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * ContactMessagesController constructor.
     *
     * @param ResponseFactory $responseFactory
     */
    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/contact_message Create
     * @apiName Create
     * @apiGroup Contact Message
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-Type application/json
     * @apiParamExample {json} Request-Body-Example:
     * {
     *     "email": "username@domain.name",
     *     "name": "John Doe",
     *     "subject": "The message subject",
     *     "text": "The message text."
     * }
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 204 No Content
     */

    /**
     * Create a resource.
     *
     * @param ContactMessageRequest $request
     * @return JsonResponse
     */
    public function create(ContactMessageRequest $request): JsonResponse
    {
        $request->merge(['client_ip_address' => $request->getClientIp()]);

        Mail::queue(new ContactMessage($request->all()));

        return $this->responseFactory->json(null, Response::HTTP_NO_CONTENT);
    }
}
