<?php

namespace Core\Entities;

use Carbon\Carbon;
use Core\ValueObjects\ImageMetadata;
use Illuminate\Support\Collection;

/**
 * Class PhotoEntity.
 *
 * @package Core\Entities
 */
final class PhotoEntity extends AbstractEntity
{
    private $id;
    private $createdByUserId;
    private $path;
    private $avgColor;
    private $metadata;
    private $createdAt;
    private $updatedAt;
    private $thumbnails;
    private $location;

    /**
     * PhotoEntity constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->setId($attributes['id'] ?? null);
        $this->setCreatedByUserId($attributes['created_by_user_id'] ?? null);
        $this->setPath($attributes['path'] ?? null);
        $this->setAvgColor($attributes['avg_color'] ?? null);
        $this->setMetadata(new ImageMetadata($attributes['metadata'] ?? []));
        $this->setCreatedAt(isset($attributes['created_at']) ? new Carbon($attributes['created_at']) : null);
        $this->setUpdatedAt(isset($attributes['updated_at']) ? new Carbon($attributes['updated_at']) : null);
        $this->setThumbnails(collect($attributes['thumbnails'] ?? [])->map(function (array $attributes) {
            return new ThumbnailEntity($attributes);
        }));
        if (isset($attributes['location'])) {
            $this->setLocation(new LocationEntity($attributes['location']));
        }
    }

    /**
     * @param int $id
     * @return $this
     */
    private function setId(int $id): PhotoEntity
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $createdByUserId
     * @return $this
     */
    private function setCreatedByUserId(int $createdByUserId): PhotoEntity
    {
        $this->createdByUserId = $createdByUserId;

        return $this;
    }

    /**
     * @return int
     */
    public function getCreatedByUserId(): int
    {
        return $this->createdByUserId;
    }

    /**
     * @param string $path
     * @return $this
     */
    private function setPath(string $path): PhotoEntity
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getDirPath(): string
    {
        return pathinfo($this->path, PATHINFO_DIRNAME);
    }

    /**
     * @param string $avgColor
     * @return $this
     */
    private function setAvgColor(string $avgColor): PhotoEntity
    {
        $this->avgColor = $avgColor;

        return $this;
    }

    /**
     * @return string
     */
    public function getAvgColor(): string
    {
        return $this->avgColor;
    }

    /**
     * @param ImageMetadata $metadata
     * @return $this
     */
    private function setMetadata(ImageMetadata $metadata): PhotoEntity
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @return ImageMetadata
     */
    public function getMetadata(): ImageMetadata
    {
        return $this->metadata;
    }

    /**
     * @param Carbon $createdAt
     * @return $this
     */
    private function setCreatedAt(Carbon $createdAt): PhotoEntity
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    /**
     * @param Carbon $updatedAt
     * @return $this
     */
    private function setUpdatedAt(Carbon $updatedAt): PhotoEntity
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    /**
     * @param Collection $thumbnails
     * @return $this
     */
    public function setThumbnails(Collection $thumbnails): PhotoEntity
    {
        $this->thumbnails = $thumbnails;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getThumbnails(): Collection
    {
        return $this->thumbnails;
    }

    /**
     * @param LocationEntity|null $location
     * @return $this
     */
    private function setLocation(?LocationEntity $location): PhotoEntity
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return LocationEntity|null
     */
    public function getLocation(): ?LocationEntity
    {
        return $this->location;
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return $this->getPath();
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'created_by_user_id' => $this->getCreatedByUserId(),
            'path' => $this->getPath(),
            'avg_color' => $this->getAvgColor(),
            'metadata' => $this->getMetadata()->toArray(),
            'created_at' => $this->getCreatedAt()->toAtomString(),
            'updated_at' => $this->getUpdatedAt()->toAtomString(),
            'location' => $this->getLocation() ? $this->getLocation()->toArray() : null,
            'thumbnails' => $this->getThumbnails()->toArray(),
        ];
    }
}
