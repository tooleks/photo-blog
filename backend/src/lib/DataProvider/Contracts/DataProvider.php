<?php

namespace Lib\DataProvider\Contracts;

use Closure;

/**
 * Interface DataProvider.
 *
 * @package Lib\DataProvider\Contracts
 */
interface DataProvider
{
    /**
     * Get model class.
     *
     * @return string
     */
    public function getModelClass() : string;

    /**
     * Reset data service to its initial state.
     *
     * @return void
     */
    public function reset();

    /**
     * Apply query criteria.
     *
     * @param Criteria $criteria
     * @return $this
     */
    public function applyCriteria(Criteria $criteria);

    /**
     * Apply query criteria if the given "value" is true.
     *
     * @param bool $value
     * @param Criteria $criteria
     * @return $this
     */
    public function applyCriteriaWhen(bool $value, Criteria $criteria);

    /**
     * Get model by unique ID.
     *
     * @param mixed $id
     * @param array $options
     * @return mixed
     */
    public function getById($id, array $options = []);

    /**
     * Get first model.
     *
     * @param array $options
     * @return mixed
     */
    public function getFirst(array $options = []);

    /**
     * Get models.
     *
     * @param array $options
     * @return mixed
     */
    public function get(array $options = []);

    /**
     * Apply callback function on each model.
     *
     * @param Closure $callback
     * @param int $chunkSize
     * @param array $options
     * @return void
     */
    public function each(Closure $callback, int $chunkSize = 100, array $options = []);

    /**
     * Get models paginator.
     *
     * @param int $page
     * @param int $perPage
     * @param array $options
     * @return mixed
     */
    public function getPaginator(int $page = 1, int $perPage = 20, array $options = []);

    /**
     * Count models.
     *
     * @param array $options
     * @return mixed
     */
    public function count(array $options = []) : int;

    /**
     * Save model.
     *
     * @param mixed $model
     * @param array $attributes
     * @param array $options
     * @return mixed
     */
    public function save($model, array $attributes = [], array $options = []);

    /**
     * Delete model.
     *
     * @param mixed $model
     * @param array $options
     * @return bool
     */
    public function delete($model, array $options = []) : bool;
}
