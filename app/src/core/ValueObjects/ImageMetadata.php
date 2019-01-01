<?php

namespace Core\ValueObjects;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * Class ImageMetadata.
 *
 * @package Core\ValueObjects
 */
final class ImageMetadata implements Arrayable, JsonSerializable
{
    private $attributes;

    /**
     * ImageMetadata constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return string|null
     */
    public function getManufacturer(): ?string
    {
        return $this->attributes['ifd0.Make'] ?? $this->attributes['exif.MakerNote'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getModel(): ?string
    {
        return $this->attributes['ifd0.Model'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getExposureTime(): ?string
    {
        $raw = $this->attributes['exif.ExposureTime'] ?? null;

        if (!is_string($raw)) {
            return null;
        }

        [$numerator, $denominator] = explode('/', $raw);

        if (!is_numeric($numerator) || !is_numeric($denominator)) {
            return null;
        }

        $value = $denominator / $numerator;

        return '1/' . round($value);
    }

    /**
     * @return string|null
     */
    public function getAperture(): ?string
    {
        $raw = $this->attributes['exif.FNumber'] ?? null;

        if (!is_string($raw)) {
            return null;
        }

        [$numerator, $denominator] = explode('/', $raw);

        if (!is_numeric($numerator) || !is_numeric($denominator)) {
            return null;
        }

        $value = $numerator / $denominator;

        return 'f/' . round($value, 1);
    }

    /**
     * @return string|null
     */
    public function getFocalLength(): ?string
    {
        $raw = $this->attributes['exif.FocalLength'] ?? null;

        if (!is_string($raw)) {
            return null;
        }

        [$numerator, $denominator] = explode('/', $raw);

        if (!is_numeric($numerator) || !is_numeric($denominator)) {
            return null;
        }

        $value = $numerator / $denominator;

        return round($value);
    }

    /**
     * @return string|null
     */
    public function getFocalLengthIn35mm(): ?string
    {
        $value = $this->attributes['exif.FocalLengthIn35mmFilm'] ?? null;

        if (!is_numeric($value)) {
            return null;
        }

        return $value;
    }

    /**
     * @return string|null
     */
    public function getIso(): ?string
    {
        return $this->attributes['exif.ISOSpeedRatings'] ?? null;
    }

    /**
     * @return Carbon|null
     */
    public function getTakenAt(): ?Carbon
    {
        $raw = $this->attributes['exif.DateTimeOriginal'] ?? null;

        if (!is_string($raw) && !is_numeric($raw)) {
            return null;
        }

        return new Carbon($raw);
    }

    /**
     * @return string|null
     */
    public function getSoftware(): ?string
    {
        return $this->attributes['ifd0.Software'] ?? null;
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        $items = collect();

        if ($this->getManufacturer()) {
            $items->push("Manufacturer: {$this->getManufacturer()}");
        }

        if ($this->getModel()) {
            $items->push("Model: {$this->getModel()}");
        }

        if ($this->getExposureTime()) {
            $items->push("Exposure time: {$this->getExposureTime()}");
        }

        if ($this->getAperture()) {
            $items->push("Aperture: {$this->getAperture()}");
        }

        if ($this->getFocalLength()) {
            $items->push("Focal length: {$this->getFocalLength()}");
        }

        if ($this->getFocalLengthIn35mm()) {
            $items->push("Focal length (35mm equivalent): {$this->getFocalLengthIn35mm()}");
        }

        if ($this->getIso()) {
            $items->push("ISO: {$this->getIso()}");
        }

        if ($this->getTakenAt()) {
            $items->push("Taken at: {$this->getTakenAt()->toAtomString()}");
        }

        if ($this->getSoftware()) {
            $items->push("Software: {$this->getSoftware()}");
        }

        return $items->implode(', ');
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
