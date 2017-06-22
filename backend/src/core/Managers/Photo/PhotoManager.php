<?php

namespace Core\Managers\Photo;

use Carbon\Carbon;
use Closure;
use Core\DataProviders\Photo\Criterias\HasSearchPhrase;
use Core\DataProviders\Photo\Criterias\HasTagWithValue;
use Core\DataProviders\Photo\Criterias\IsPublished;
use Core\Managers\Photo\Contracts\PhotoManager as PhotoManagerContract;
use Core\Managers\Trash\Contracts\TrashManager;
use Core\DataProviders\Photo\Contracts\PhotoDataProvider;
use Core\Models\Photo;
use Core\Services\Photo\Contracts\ExifFetcherService;
use Core\Services\Photo\Contracts\ThumbnailsGeneratorService;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Http\UploadedFile;
use Lib\DataProvider\Criterias\SortByCreatedAt;
use Lib\DataProvider\Criterias\WhereUpdatedAtLessThan;
use Throwable;
use Tooleks\Php\AvgColorPicker\Contracts\AvgColorPicker;

/**
 * Class PhotoManager.
 *
 * @package Core\Managers\Photo
 */
class PhotoManager implements PhotoManagerContract
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var TrashManager
     */
    private $trashManager;

    /**
     * @var PhotoDataProvider
     */
    private $photoDataProvider;

    /**
     * @var ExifFetcherService
     */
    private $exifFetcher;

    /**
     * @var ThumbnailsGeneratorService
     */
    private $thumbnailsGenerator;

    /**
     * @var AvgColorPicker
     */
    private $avgColorPicker;

    /**
     * PhotoManager constructor.
     *
     * @param Storage $storage
     * @param TrashManager $trashManager
     * @param PhotoDataProvider $photoDataProvider
     * @param ExifFetcherService $exifFetcher
     * @param ThumbnailsGeneratorService $thumbnailsGenerator
     * @param AvgColorPicker $avgColorPicker
     */
    public function __construct(
        Storage $storage,
        TrashManager $trashManager,
        PhotoDataProvider $photoDataProvider,
        ExifFetcherService $exifFetcher,
        ThumbnailsGeneratorService $thumbnailsGenerator,
        AvgColorPicker $avgColorPicker
    )
    {
        $this->storage = $storage;
        $this->trashManager = $trashManager;
        $this->photoDataProvider = $photoDataProvider;
        $this->exifFetcher = $exifFetcher;
        $this->thumbnailsGenerator = $thumbnailsGenerator;
        $this->avgColorPicker = $avgColorPicker;
    }

    /**
     * @inheritdoc
     */
    public function paginateOverPublished(int $page, int $perPage, array $attributes = [])
    {
        return $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->applyCriteriaWhen(isset($attributes['tag']), new HasTagWithValue($attributes['tag'] ?? null))
            ->applyCriteriaWhen(isset($attributes['search_phrase']), new HasSearchPhrase($attributes['search_phrase'] ?? null))
            ->applyCriteria((new SortByCreatedAt)->desc())
            ->getPaginator($page, $perPage);
    }

    /**
     * @inheritdoc
     */
    public function eachNotPublishedOlderThanWeek(Closure $callback)
    {
        $this->photoDataProvider
            ->applyCriteria(new IsPublished(false))
            ->applyCriteria(new WhereUpdatedAtLessThan((new Carbon)->addWeek('-1')))
            ->each($callback);
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id)
    {
        return $this->photoDataProvider->getById($id);
    }

    /**
     * @inheritdoc
     */
    public function getPublishedById(int $id)
    {
        return $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->getById($id);
    }

    /**
     * @inheritdoc
     */
    public function getNotPublishedById(int $id)
    {
        return $this->photoDataProvider
            ->applyCriteria(new IsPublished(false))
            ->getById($id);
    }

    /**
     * @inheritdoc
     */
    public function saveWithAttributes(Photo $photo, array $attributes)
    {
        $this->photoDataProvider->save($photo, $attributes, ['with' => ['tags']]);
    }

    /**
     * @inheritdoc
     */
    public function saveWithUploadedFile(Photo $photo, UploadedFile $file)
    {
        $oldPhotoDirectoryPath = dirname($photo->path);
        $newPhotoDirectoryPath = config('main.storage.path.photos') . '/' . str_random(10);

        try {
            $photo->path = $this->storage->put($newPhotoDirectoryPath, $file);
            $photo->avg_color = $this->avgColorPicker->getImageAvgHexByPath(
                $this->storage->getDriver()->getAdapter()->getPathPrefix() . $photo->path
            );
            $this->photoDataProvider->save(
                $photo,
                ['exif' => $this->exifFetcher->run($file), 'thumbnails' => $this->thumbnailsGenerator->run($photo->path)],
                ['with' => ['exif', 'thumbnails']]
            );
        } catch (Throwable $e) {
            $this->trashManager->moveIfExists($newPhotoDirectoryPath);
            throw $e;
        }

        $this->trashManager->moveIfExists($oldPhotoDirectoryPath);
    }

    /**
     * @inheritdoc
     */
    public function deleteWithRelations(Photo $photo)
    {
        try {
            $this->trashManager->moveIfExists(dirname($photo->path));
            $this->photoDataProvider->delete($photo);
        } catch (Throwable $e) {
            $this->trashManager->restoreIfExists(dirname($photo->path));
            throw $e;
        }
    }
}
