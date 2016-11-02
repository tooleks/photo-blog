<?php

namespace App\Services\Photos\Contracts;

use App\Models\Photo;
use App\Services\Photos\Contracts\PhotoExceptionContract;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface PhotoServiceContract
 * @package App\Services\Photos\Contracts
 */
interface PhotoServiceContract
{
    /**
     * Set action scenario.
     *
     * @param string $scenario
     * @return PhotoServiceContract
     */
    public function setScenario(string $scenario);

    /**
     * Create photo model instance.
     *
     * @param array $attributes
     * @param bool $validate
     * @throws PhotoExceptionContract
     * @return Photo
     */
    public function create(array $attributes, bool $validate = true) : Photo;

    /**
     * Save photo model instance.
     *
     * @param Photo $photo
     * @param array $attributes
     * @param bool $validate
     * @throws PhotoExceptionContract
     * @return Photo
     */
    public function save(Photo $photo, array $attributes, bool $validate = true) : Photo;

    /**
     * Delete photo model instance.
     *
     * @param Photo $photo
     * @throws PhotoExceptionContract
     * @return int
     */
    public function delete(Photo $photo) : int;

    /**
     * Get photo model instance by ID.
     *
     * @param int $id
     * @throws PhotoExceptionContract
     * @return Photo
     */
    public function getById(int $id) : Photo;

    /**
     * Get all photo model instances.
     *
     * @param int $take
     * @param int $skip
     * @throws PhotoExceptionContract
     * @return Collection
     */
    public function get(int $take = 10, int $skip = 0) : Collection;

    /**
     * Get all photo model instances by search parameters.
     *
     * @param int $take
     * @param int $skip
     * @param array $parameters
     * @throws PhotoExceptionContract
     * @return Collection
     */
    public function getBySearchParameters(int $take = 10, int $skip = 0, array $parameters) : Collection;
}
