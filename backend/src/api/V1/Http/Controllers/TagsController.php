<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\FindTags;
use Core\DataServices\Tag\Contracts\TagDataService;
use Core\DataServices\Tag\Criterias\OrderByPhotosCountDesc;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Lib\DataService\Criterias\Skip;
use Lib\DataService\Criterias\Take;

/**
 * Class TagsController.
 *
 * @property TagDataService tagDataService
 * @package Api\V1\Http\Controllers
 */
class TagsController extends ResourceController
{
    /**
     * TagsController constructor.
     *
     * @param Request $request
     * @param Guard $guard
     * @param string $presenterClass
     * @param TagDataService $tagDataService
     */
    public function __construct(
        Request $request,
        Guard $guard,
        string $presenterClass,
        TagDataService $tagDataService
    )
    {
        parent::__construct($request, $guard, $presenterClass);

        $this->tagDataService = $tagDataService;
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/tags Find
     * @apiName Find
     * @apiGroup Tags
     * @apiHeader {String} Accept application/json
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "status": true,
     *      "data": [
     *          {
     *              "text": "nature"
     *          },
     *          {
     *              "text": "city"
     *          },
     *          {
     *              "text": "sky"
     *          },
     *          {
     *              "text": "water"
     *          }
     *      ]
     *  }
     */

    /**
     * Find photos.
     *
     * @param FindTags $request
     * @return Collection
     */
    public function find(FindTags $request) : Collection
    {
        $tags = $this->tagDataService
            ->applyCriteria(new Skip($request->get('skip', 0)))
            ->applyCriteria(new Take($request->get('take', 10)))
            ->applyCriteria(new OrderByPhotosCountDesc)
            ->get();

        return $tags;
    }
}
