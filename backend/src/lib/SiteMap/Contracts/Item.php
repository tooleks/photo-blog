<?php

namespace Lib\SiteMap\Contracts;

/**
 * Interface Item.
 *
 * @package Lib\SiteMap\Contracts
 */
interface Item
{
    /**
     * Item constructor.
     *
     * @param string $location
     * @param string $lastModified
     * @param string $changeFrequency
     * @param string $priority
     */
    public function __construct(
        string $location = '',
        string $lastModified = '',
        string $changeFrequency = '',
        string $priority = ''
    );

    /**
     * @param string $value
     * @return $this
     */
    public function setLocation(string $value);

    /**
     * @return string
     */
    public function getLocation(): string;

    /**
     * @return bool
     */
    public function hasLocation(): bool;

    /**
     * @param string $value
     * @return $this
     */
    public function setLastModified(string $value);

    /**
     * @return string
     */
    public function getLastModified(): string;

    /**
     * @return bool
     */
    public function hasLastModified(): bool;

    /**
     * @param string $value
     * @return $this
     */
    public function setChangeFrequency(string $value);

    /**
     * @return string
     */
    public function getChangeFrequency(): string;

    /**
     * @return bool
     */
    public function hasChangeFrequency(): bool;

    /**
     * @param string $value
     * @return $this
     */
    public function setPriority(string $value);

    /**
     * @return string
     */
    public function getPriority(): string;

    /**
     * @return bool
     */
    public function hasPriority(): bool;
}
