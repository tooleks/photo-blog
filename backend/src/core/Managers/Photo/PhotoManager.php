<?php

namespace Core\Managers\Photo;

use Carbon\Carbon;
use Closure;
use Core\DataProviders\Photo\Criterias\HasSearchPhrase;
use Core\DataProviders\Photo\Criterias\HasTagWithValue;
use Core\DataProviders\Photo\Criterias\IsPublished;
use Core\DataProviders\Photo\Contracts\PhotoDataProvider;
use Core\Managers\Photo\Contracts\PhotoManager as PhotoManagerContract;
use Core\Services\Trash\Contracts\TrashServiceException;
use Core\Services\Trash\Contracts\TrashService;
use Core\Models\Photo;
use Core\Services\Photo\Contracts\ExifFetcherService;
use Core\Services\Photo\Contracts\ThumbnailsGeneratorService;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
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
     * @var TrashService
     */
    private $trashService;

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
     * @param TrashService $trashService
     * @param PhotoDataProvider $photoDataProvider
     * @param ExifFetcherService $exifFetcher
     * @param ThumbnailsGeneratorService $thumbnailsGenerator
     * @param AvgColorPicker $avgColorPicker
     */
    public function __construct(
        Storage $storage,
        TrashService $trashService,
        PhotoDataProvider $photoDataProvider,
        ExifFetcherService $exifFetcher,
        ThumbnailsGeneratorService $thumbnailsGenerator,
        AvgColorPicker $avgColorPicker
    )
    {
        $this->storage = $storage;
        $this->trashService = $trashService;
        $this->photoDataProvider = $photoDataProvider;
        $this->exifFetcher = $exifFetcher;
        $this->thumbnailsGenerator = $thumbnailsGenerator;
        $this->avgColorPicker = $avgColorPicker;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id): Photo
    {
        return $this->photoDataProvider->getById($id);
    }

    /**
     * @inheritdoc
     */
    public function getPublishedById(int $id): Photo
    {
        return $this->photoDataProvider
            ->applyCriteria(new IsPublished(true))
            ->getById($id);
    }

    /**
     * @inheritdoc
     */
    public function getNotPublishedById(int $id): Photo
    {
        return $this->photoDataProvider
            ->applyCriteria(new IsPublished(false))
            ->getById($id);
    }

    /**
     * @inheritdoc
     */
    public function getLastFiftyPublished(): Collection
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
        $oldDirectoryPath = dirname($photo->path);
        $newDirectoryPath = sprintf('%s/%s', config('main.storage.path.photos'), str_random(10));

        try {
            $photo->path = $this->storage->put($newDirectoryPath, $file);
            $photo->avg_color = $this->avgColorPicker->getImageAvgHexByPath($this->storage->getDriver()->getAdapter()->getPathPrefix() . $photo->path);
            $attributes = ['exif' => $this->exifFetcher->fetchFromUploadedFile($file), 'thumbnails' => $this->thumbnailsGenerator->generateByFilePath($photo->path)];
            $this->photoDataProvider->save($photo, $attributes, ['with' => ['exif', 'thumbnails']]);
            $this->trashService->moveIfExists($oldDirectoryPath);
        } catch (TrashServiceException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->trashService->restoreIfExists($oldDirectoryPath);
            $this->trashService->moveIfExists($newDirectoryPath);
            throw $e;
        }
    }

    /**
     * @inheritdoc
     */
    public function delete(Photo $photo)
    {
        $directoryPath = dirname($photo->path);

        try {
            $this->trashService->moveIfExists($directoryPath);
            $this->photoDataProvider->delete($photo);
        } catch (TrashServiceException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->trashService->restoreIfExists($directoryPath);
            throw $e;
        }
    }
}
