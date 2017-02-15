<?php

namespace Lib\Repositories;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Lib\Repositories\Contracts\Criteria;
use Lib\Repositories\Contracts\Repository as RepositoryContract;
use Lib\Repositories\Exceptions\RepositoryException;
use Lib\Repositories\Exceptions\RepositoryNotFoundException;
use Throwable;

/**
 * Class Repository.
 *
 * Something that looks like repository. =)
 *
 * @property ConnectionInterface dbConnection
 * @package Lib\Repositories
 */
abstract class Repository implements RepositoryContract
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
     * Search query criterias.
     *
     * @var array
     */
    protected $criterias;

    /**
     * Repository constructor.
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
    protected function resetModel()
    {
        $modelClass = $this->getModelClass();

        $this->model = new $modelClass;
    }

    /**
     * Reset model query class instance.
     *
     * @return void
     */
    protected function resetQuery()
    {
        $this->query = $this->model->newQuery();
    }

    /**
     * Reset search query criterias.
     *
     * @return void
     */
    protected function resetCriterias()
    {
        $this->criterias = [];
    }

    /**
     * Reset repository to initial state.
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
     * Apply search criterias to the model query class instance.
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
            throw new RepositoryNotFoundException(sprintf('%s not found.', class_basename($this->getModelClass())));
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
            throw new RepositoryNotFoundException(sprintf('%s not found.', class_basename($this->getModelClass())));
        }

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function getAll()
    {
        $this->applyCriterias();

        $models = $this->query->get();

        $this->reset();

        return $models;
    }

    /**
     * Assert model.
     *
     * @param mixed $model
     * @throws RepositoryException
     */
    protected function assertModel($model)
    {
        $modelClass = $this->getModelClass();

        if (!($model instanceof $modelClass)) {
            throw new RepositoryException(sprintf('Model must be a %s type.', $modelClass));
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
            throw $e;
        }
    }

    /**
     * Save model relations.
     *
     * @param mixed $model
     * @param array $attributes
     * @param array $relations
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

        return $model->delete();
    }
}
