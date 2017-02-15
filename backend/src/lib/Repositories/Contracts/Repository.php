<?php

namespace Lib\Repositories\Contracts;

/**
 * Interface Repository.
 *
 * @package Lib\Repositories\Contracts
 */
interface Repository
{
    /**
     * Get model class.
     *
     * @return string
     */
    public function getModelClass() : string;

    /**
     * Relations that will be loaded.
     *
     * @param array $relations
     * @return $this
     */
    public function withRelations(array $relations);

    /**
     * Push search query criteria.
     *
     * @param Criteria|null $criteria
     * @return $this
     */
    public function pushCriteria($criteria);

    /**
     * Get model by unique ID.
     *
     * @param mixed $id
     * @return mixed
     */
    public function getById($id);

    /**
     * Get first model.
     *
     * @return mixed
     */
    public function getFirst();

    /**
     * Get all models.
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Save model.
     *
     * @param mixed $model
     * @param array $attributes
     * @return mixed
     */
    public function save($model, array $attributes = []);

    /**
     * Delete model.
     *
     * @param mixed $model
     * @return bool
     */
    public function delete($model) : bool;
}
