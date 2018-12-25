<?php

namespace Api\V1\Http\Actions;

use Api\V1\Http\Requests\ContactMessageRequest;
use App\Mail\ContactMessage;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

/**
 * Class ContactMessageSendAction.
 *
 * @package Api\V1\Http\Actions
 */
class ContactMessageSendAction
{
    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * ContactMessageSendAction constructor.
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
     *     "message": "The message text."
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
    public function __invoke(ContactMessageRequest $request): JsonResponse
    {
        $request->merge(['client_ip_address' => $request->getClientIp()]);

        Mail::queue(new ContactMessage($request->all()));

        return $this->responseFactory->json(null, Response::HTTP_NO_CONTENT);
    }
}
