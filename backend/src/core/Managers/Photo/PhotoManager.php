<?php

namespace Core\Managers\Photo;

use Carbon\Carbon;
use Closure;
use Core\DataProviders\Photo\Criterias\HasSearchPhrase;
use Core\DataProviders\Photo\Criterias\HasTagWithValue;
use Core\DataProviders\Photo\Criterias\IsPublished;
use Core\DataProviders\Photo\Contracts\PhotoDataProvider;
use Core\Managers\Photo\Contracts\PhotoManager as PhotoManagerContract;
use Core\Managers\Trash\Contracts\TrashManagerException;
use Core\Managers\Trash\Contracts\TrashManager;
use Core\Models\Photo;
use Core\Services\Photo\Contracts\ExifFetcherService;
use Core\Services\Photo\Contracts\ThumbnailsGeneratorService;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Http\UploadedFile;
use Lib\DataProvider\Criterias\SortByCreatedAt;
use Lib\DataProvider\Criterias\Take;
use Lib\DataProvider\Criterias\WhereCreatedAtGreaterThan;
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
    public function getLastFiftyPublished()
    {
        return $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->applyCriteria((new SortByCreatedAt)->desc())
            ->applyCriteria(new Take(50))
            ->get();
    }

    /**
     * @inheritdoc
     */
    public function paginateOverPublished(int $page, int $perPage, array $query = [])
    {
        return $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->applyCriteriaWhen(isset($query['tag']), new HasTagWithValue($query['tag'] ?? null))
            ->applyCriteriaWhen(isset($query['search_phrase']), new HasSearchPhrase($query['search_phrase'] ?? null))
            ->applyCriteria((new SortByCreatedAt)->desc())
            ->paginate($page, $perPage);
    }

    /**
     * @inheritdoc
     */
    public function each(Closure $callback)
    {
        $this->photoDataProvider->each($callback);
    }

    /**
     * @inheritdoc
     */
    public function eachPublished(Closure $callback)
    {
        $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->each($callback);
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
    public function existsPublishedOlderThanWeek()
    {
        $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->applyCriteria(new WhereCreatedAtGreaterThan((new Carbon)->addWeek('-1')))
            ->exists();
    }

    /**
     * @inheritdoc
     */
    public function save(Photo $photo, array $attributes = [], array $options = [])
    {
        $this->photoDataProvider->save($photo, $attributes, $options);
    }

    /**
     * @inheritdoc
     */
    public function saveWithFile(Photo $photo, UploadedFile $file)
    {
        $oldPhotoDirectoryPath = dirname($photo->path);
        $newPhotoDirectoryPath = sprintf('%s/%s', config('main.storage.path.photos'), str_random(10));

        try {
            $photo->path = $this->storage->put($newPhotoDirectoryPath, $file);
            $photo->avg_color = $this->avgColorPicker->getImageAvgHexByPath($this->storage->getDriver()->getAdapter()->getPathPrefix() . $photo->path);
            $attributes = ['exif' => $this->exifFetcher->run($file), 'thumbnails' => $this->thumbnailsGenerator->run($photo->path)];
            $this->photoDataProvider->save($photo, $attributes, ['with' => ['exif', 'thumbnails']]);
            $this->trashManager->moveIfExists($oldPhotoDirectoryPath);
        } catch (TrashManagerException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->trashManager->restoreIfExists($oldPhotoDirectoryPath);
            $this->trashManager->moveIfExists($newPhotoDirectoryPath);
            throw $e;
        }
    }

    /**
     * @inheritdoc
     */
    public function delete(Photo $photo)
    {
        $photoDirectoryPath = dirname($photo->path);

        try {
            $this->trashManager->moveIfExists($photoDirectoryPath);
            $this->photoDataProvider->delete($photo);
        } catch (TrashManagerException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->trashManager->restoreIfExists($photoDirectoryPath);
            throw $e;
        }
    }
}
