<?php

namespace Lib\DataService\Contracts;

/**
 * Interface DataService.
 *
 * @package Lib\DataService\Contracts
 */
interface DataService
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
