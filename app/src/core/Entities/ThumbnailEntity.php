<?php

namespace Core\Entities;

/**
 * Class ThumbnailEntity.
 *
 * @package Core\Entities
 */
final class ThumbnailEntity extends AbstractEntity
{
    private $path;
    private $width;
    private $height;

    /**
     * ThumbnailEntity constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->setPath($attributes['path'] ?? null);
        $this->setWidth($attributes['width'] ?? null);
        $this->setHeight($attributes['height'] ?? null);
    }

    /**
     * @param string $path
     * @return $this
     */
    private function setPath(string $path): ThumbnailEntity
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
     * @param int $width
     * @return $this
     */
    private function setWidth(int $width): ThumbnailEntity
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $height
     * @return $this
     */
    private function setHeight(int $height): ThumbnailEntity
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
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
            'path' => $this->getPath(),
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
        ];
    }
}
