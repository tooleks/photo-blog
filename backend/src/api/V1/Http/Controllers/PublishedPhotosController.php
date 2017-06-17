<?php

namespace Api\V1\Http\Controllers;

use Api\V1\Http\Requests\CreatePublishedPhotoRequest;
use Api\V1\Http\Requests\FindPublishedPhotosRequest;
use Api\V1\Http\Requests\UpdatePublishedPhotoRequest;
use Core\Models\Photo;
use Core\DataProviders\Photo\Criterias\IsPublished;
use Core\DataProviders\Photo\Criterias\HasSearchPhrase;
use Core\DataProviders\Photo\Criterias\HasTagWithValue;
use Core\DataProviders\Photo\Contracts\PhotoDataProvider;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Routing\Controller;
use Lib\DataProvider\Criterias\SortByCreatedAt;

/**
 * Class PublishedPhotosController.
 *
 * @property PhotoDataProvider photoDataProvider
 * @package Api\V1\Http\Controllers
 */
class PublishedPhotosController extends Controller
{
    /**
     * PublishedPhotosController constructor.
     *
     * @param PhotoDataProvider $photoDataProvider
     */
    public function __construct(PhotoDataProvider $photoDataProvider)
    {
        $this->photoDataProvider = $photoDataProvider;
    }

    /**
     * @apiVersion 1.0.0
     * @api {post} /v1/published_photos Create
     * @apiName Create
     * @apiGroup Published Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type application/json
     * @apiParam {Integer{1..N}} photo_id Unique resource ID.
     * @apiParam {Integer{1..65535}} description Description.
     * @apiParam {Object[]} tags Tags collection.
     * @apiParam {String{1..255}} tags.value Tag value.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 201 Created
     * {
     *     "id": 1,
     *     "created_by_user_id" 1,
     *     "url": "http://path/to/photo/file",
     *     "avg_color": "#000000",
     *     "created_at": "2016-10-24 12:24:33",
     *     "updated_at": "2016-10-24 14:38:05",
     *     "exif": {
     *         "manufacturer": "Manufacturer Name",
     *         "model": "Model Number",
     *         "exposure_time": "1/160",
     *         "aperture": "f/11.0",
     *         "iso": 200,
     *         "taken_at": "2016-10-24 12:24:33"
     *     },
     *     "thumbnails": [
     *         "medium": {
     *             "url": "http://path/to/photo/thumbnail/medium_file"
     *             "width": 500,
     *             "height": 500
     *         },
     *         "large": {
     *              "url": "http://path/to/photo/thumbnail/large_file"
     *              "width": 1000,
     *              "height": 1000
     *         }
     *     ],
     *     "tags": [
     *         {
     *             "value": "nature"
     *         }
     *     ]
     * }
     */

    /**
     * Create a photo.
     *
     * @param CreatePublishedPhotoRequest $request
     * @return Photo
     */
    public function create(CreatePublishedPhotoRequest $request): Photo
    {
        $photo = $this->photoDataProvider
            ->applyCriteria(new IsPublished(false))
            ->getById($request->get('photo_id'), ['with' => ['exif', 'thumbnails', 'tags']]);

        $photo->setIsPublishedAttribute(true);

        $this->photoDataProvider->save($photo, $request->all(), ['with' => ['tags']]);

        return $photo;
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/published_photos/:id Get
     * @apiName Get
     * @apiGroup Published Photos
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *     "id": 1,
     *     "created_by_user_id" 1,
     *     "url": "http://path/to/photo/file",
     *     "avg_color": "#000000",
     *     "created_at": "2016-10-24 12:24:33",
     *     "updated_at": "2016-10-24 14:38:05",
     *     "exif": {
     *         "manufacturer": "Manufacturer Name",
     *         "model": "Model Number",
     *         "exposure_time": "1/160",
     *         "aperture": "f/11.0",
     *         "iso": 200,
     *         "taken_at": "2016-10-24 12:24:33"
     *     },
     *     "thumbnails": [
     *         "medium": {
     *             "url": "http://path/to/photo/thumbnail/medium_file"
     *             "width": 500,
     *             "height": 500
     *         },
     *         "large": {
     *              "url": "http://path/to/photo/thumbnail/large_file"
     *              "width": 1000,
     *              "height": 1000
     *         }
     *     ],
     *     "tags": [
     *         {
     *             "value": "nature"
     *         }
     *     ]
     * }
     */

    /**
     * Get a photo.
     *
     * @param Photo $photo
     * @return Photo
     */
    public function get(Photo $photo): Photo
    {
        return $photo;
    }

    /**
     * @apiVersion 1.0.0
     * @api {get} /v1/published_photos Find
     * @apiName Find
     * @apiGroup Published Photos
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} [page=1]
     * @apiParam {Integer{1..100}} [per_page=20]
     * @apiParam {String{1..255}} [tag] Tag to search by.
     * @apiParam {String{1..255}} [search_phrase] Search phrase to search by.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *     "total": 100,
     *     "per_page": 10,
     *     "current_page": 2,
     *     "last_page": 10,
     *     "next_page_url": "http://path/to/api/resource?page=3",
     *     "prev_page_url": "http://path/to/api/resource?page=1",
     *     "from": 10,
     *     "to": 20,
     *     "data": [
     *         {
     *             "id": 1,
     *             "created_by_user_id" 1,
     *             "url": "http://path/to/photo/file",
     *             "avg_color": "#000000",
     *             "created_at": "2016-10-24 12:24:33",
     *             "updated_at": "2016-10-24 14:38:05",
     *             "exif": {
     *                 "manufacturer": "Manufacturer Name",
     *                 "model": "Model Number",
     *                 "exposure_time": "1/160",
     *                 "aperture": "f/11.0",
     *                 "iso": 200,
     *                 "taken_at": "2016-10-24 12:24:33"
     *             },
     *             "thumbnails": [
     *                 "medium": {
     *                     "url": "http://path/to/photo/thumbnail/medium_file"
     *                     "width": 500,
     *                     "height": 500
     *                 },
     *                 "large": {
     *                      "url": "http://path/to/photo/thumbnail/large_file"
     *                      "width": 1000,
     *                      "height": 1000
     *                 }
     *             ],
     *             "tags": [
     *                 {
     *                     "value": "nature"
     *                 }
     *             ]
     *         }
     *     ]
     * }
     */

    /**
     * Find photos.
     *
     * @param FindPublishedPhotosRequest $request
     * @return AbstractPaginator
     */
    public function find(FindPublishedPhotosRequest $request): AbstractPaginator
    {
        $paginator = $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->applyCriteriaWhen($request->has('tag'), new HasTagWithValue($request->get('tag')))
            ->applyCriteriaWhen($request->has('search_phrase'), new HasSearchPhrase($request->get('search_phrase')))
            ->applyCriteria((new SortByCreatedAt)->desc())
            ->getPaginator($request->get('page', 1), $request->get('per_page', 20), ['with' => ['exif', 'thumbnails', 'tags']])
            ->appends($request->query());

        return $paginator;
    }

    /**
     * @apiVersion 1.0.0
     * @api {put} /v1/published_photos/:id Update
     * @apiName Update
     * @apiGroup Published Photos
     * @apiHeader {String} Accept application/json
     * @apiHeader {String} Content-type application/json
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiParam {Integer{1..65535}} description Description.
     * @apiParam {Object[]} tags Tags collection.
     * @apiParam {String{1..255}} tags.value Tag value.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 201 Created
     * {
     *     "id": 1,
     *     "created_by_user_id" 1,
     *     "url": "http://path/to/photo/file",
     *     "avg_color": "#000000",
     *     "created_at": "2016-10-24 12:24:33",
     *     "updated_at": "2016-10-24 14:38:05",
     *     "exif": {
     *         "manufacturer": "Manufacturer Name",
     *         "model": "Model Number",
     *         "exposure_time": "1/160",
     *         "aperture": "f/11.0",
     *         "iso": 200,
     *         "taken_at": "2016-10-24 12:24:33"
     *     },
     *     "thumbnails": [
     *         "medium": {
     *             "url": "http://path/to/photo/thumbnail/medium_file"
     *             "width": 500,
     *             "height": 500
     *         },
     *         "large": {
     *              "url": "http://path/to/photo/thumbnail/large_file"
     *              "width": 1000,
     *              "height": 1000
     *         }
     *     ],
     *     "tags": [
     *         {
     *             "value": "nature"
     *         }
     *     ]
     * }
     */

    /**
     * Update a photo.
     *
     * @param UpdatePublishedPhotoRequest $request
     * @param Photo $photo
     * @return Photo
     */
    public function update(UpdatePublishedPhotoRequest $request, Photo $photo): Photo
    {
        $this->photoDataProvider->save($photo, $request->all(), ['with' => ['tags']]);

        return $photo;
    }

    /**
     * @apiVersion 1.0.0
     * @api {delete} /v1/published_photos/:id Delete
     * @apiName Delete
     * @apiGroup Published Photos
     * @apiHeader {String} Accept application/json
     * @apiParam {Integer{1..N}} :id Unique resource ID.
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 204 No Content
     */

    /**
     * Delete a photo.
     *
     * @param Photo $photo
     * @return void
     */
    public function delete(Photo $photo)
    {
        $this->photoDataProvider->delete($photo);
    }
}
