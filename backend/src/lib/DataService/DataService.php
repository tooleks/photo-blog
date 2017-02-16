<?php

namespace Lib\DataService;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Lib\DataService\Contracts\Criteria;
use Lib\DataService\Contracts\DataService as DataServiceContract;
use Lib\DataService\Exceptions\DataServiceDeletingException;
use Lib\DataService\Exceptions\DataServiceException;
use Lib\DataService\Exceptions\DataServiceNotFoundException;
use Lib\DataService\Exceptions\DataServiceSavingException;
use Throwable;

/**
 * Class DataService.
 *
 * @property ConnectionInterface dbConnection
 * @package Lib\DataService
 */
abstract class DataService implements DataServiceContract
{
    /**
     * Model class instance.
     *
     * @var mixed
     */
    protected $model;

    /**
     * Model query class instance.
     *
     * @var mixed
     */
    protected $query;

    /**
     * Query criterias.
     *
     * @var array
     */
    protected $criterias;

    /**
     * DataService constructor.
     *
     * @param ConnectionInterface $dbConnection
     */
    public function __construct(ConnectionInterface $dbConnection)
    {
        $this->dbConnection = $dbConnection;

        $this->reset();
    }

    /**
     * Reset model class instance.
     *
     * @return void
     */
    private function resetModel()
    {
        $modelClass = $this->getModelClass();

        $this->model = new $modelClass;
    }

    /**
     * Reset model query class instance.
     *
     * @return void
     */
    private function resetQuery()
    {
        $this->query = $this->model->newQuery();
    }

    /**
     * Reset query criterias.
     *
     * @return void
     */
    private function resetCriterias()
    {
        $this->criterias = [];
    }

    /**
     * Reset data service to initial state.
     *
     * @return void
     */
    protected function reset()
    {
        $this->resetModel();
        $this->resetQuery();
        $this->resetCriterias();
    }

    /**
     * @inheritdoc
     */
    abstract public function getModelClass() : string;

    /**
     * Apply query criterias to the model query class instance.
     *
     * @return void
     */
    protected function applyCriterias()
    {
        foreach ($this->criterias as $criteria) {
            $this->query = $criteria->apply($this->query);
        }
    }

    /**
     * @inheritdoc
     */
    public function withRelations(array $relations)
    {
        foreach ($relations as $relationName) {
            $this->query = $this->query->with($relationName);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function pushCriteria($criteria)
    {
        if ($criteria instanceof Criteria) {
            array_push($this->criterias, $criteria);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getById($id)
    {
        $this->applyCriterias();

        $model = $this->query->find($id);

        $this->reset();

        if (is_null($model)) {
            throw new DataServiceNotFoundException(sprintf('%s not found.', class_basename($this->getModelClass())));
        }

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function getFirst()
    {
        $this->applyCriterias();

        $model = $this->query->first();

        $this->reset();

        if (is_null($model)) {
            throw new DataServiceNotFoundException(sprintf('%s not found.', class_basename($this->getModelClass())));
        }

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function get()
    {
        $this->applyCriterias();

        $models = $this->query->get();

        $this->reset();

        return $models;
    }

    /**
     * @inheritdoc
     */
    public function count() : int
    {
        $this->applyCriterias();

        $count = $this->query->count();

        $this->reset();

        return $count;
    }

    /**
     * Assert model.
     *
     * @param mixed $model
     * @throws DataServiceException
     * @return void
     */
    protected function assertModel($model)
    {
        $modelClass = $this->getModelClass();

        if (!($model instanceof $modelClass)) {
            throw new DataServiceException(sprintf('Model must be a %s type.', $modelClass));
        }
    }

    /**
     * @inheritdoc
     */
    public function save($model, array $attributes = [], array $relations = [])
    {
        $this->assertModel($model);

        $model->fill($attributes);

        try {
            $this->dbConnection->beginTransaction();
            $model->save();
            $this->saveModelRelations($model, $attributes, $relations);
            $this->dbConnection->commit();
        } catch (Throwable $e) {
            $this->dbConnection->rollBack();
            throw new DataServiceSavingException($e->getMessage());
        }
    }

    /**
     * Save model relations.
     *
     * @param mixed $model
     * @param array $attributes
     * @param array $relations
     * @return void
     */
    protected function saveModelRelations($model, array $attributes, array $relations)
    {
        foreach ($relations as $relationName) {
            if (method_exists($model, $relationName)) {
                $relation = $model->{$relationName}();
                if ($relation instanceof BelongsToMany || $relation instanceof HasMany) {
                    $model->{$relationName}()->delete();
                    $model->{$relationName}()->detach();
                    $relationRecords = $model->{$relationName}()->createMany($attributes[$relationName] ?? []);
                    $model->{$relationName} = new Collection($relationRecords);
                } elseif ($relation instanceof BelongsTo || $relation instanceof HasOne) {
                    $model->{$relationName}()->delete();
                    $relationRecord = $model->{$relationName}()->create($attributes[$relationName] ?? []);
                    $model->{$relationName} = $relationRecord;
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function delete($model) : bool
    {
        $this->assertModel($model);

        try {
            $count = $model->delete();
        } catch (Throwable $e) {
            throw new DataServiceDeletingException($e->getMessage());
        }

        return $count;
    }
}
