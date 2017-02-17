<?php

namespace Lib\DataService;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionInterface as Connection;
use Illuminate\Database\Eloquent\Model;
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
 * @property Connection dbConnection
 * @property Dispatcher events
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
     * DataService constructor.
     *
     * @param Connection $dbConnection
     * @param Dispatcher $events
     */
    public function __construct(Connection $dbConnection, Dispatcher $events)
    {
        $this->dbConnection = $dbConnection;
        $this->events = $events;

        $this->reset();
    }

    /**
     * Reset model class instance.
     *
     * @return void
     * @throws DataServiceException
     */
    protected function resetModel()
    {
        $modelClass = $this->getModelClass();

        if (!class_exists($modelClass)) {
            throw new DataServiceException(sprintf('The %s class does not exist.', $modelClass));
        }

        if (!is_subclass_of($modelClass, Model::class)) {
            throw new DataServiceException(sprintf('The model must be inherited from the %s class.', Model::class));
        }

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
     * @inheritdoc
     */
    public function reset()
    {
        $this->resetModel();
        $this->resetQuery();
    }

    /**
     * Assert model.
     *
     * @param mixed $model
     * @return void
     * @throws DataServiceException
     */
    protected function assertModel($model)
    {
        $modelClass = $this->getModelClass();

        if (!($model instanceof $modelClass)) {
            throw new DataServiceException(sprintf('The model must be an instance of the %s class.', $modelClass));
        }
    }

    /**
     * Dispatch an event.
     *
     * @param string $eventName
     * @param array $payload
     * @return void
     */
    protected function dispatchEvent(string $eventName, array $payload = [])
    {
        $event = implode('.', ['events', lcfirst(class_basename(static::class)), $eventName]);

        $this->events->dispatch($event, $payload);
    }

    /**
     * @inheritdoc
     */
    abstract public function getModelClass() : string;

    /**
     * @inheritdoc
     */
    public function applyCriteria($criteria)
    {
        if ($criteria instanceof Criteria) {
            $criteria->apply($this->query);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getById($id, array $options = [])
    {
        $this->dispatchEvent('beforeGetById', ['query' => $this->query, 'options' => $options]);
        $model = $this->query->find($id);
        $this->dispatchEvent('afterGetById', ['query' => $this->query, 'model' => $model, 'options' => $options]);

        $this->reset();

        if (is_null($model)) {
            throw new DataServiceNotFoundException(sprintf('%s not found.', class_basename($this->getModelClass())));
        }

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function getFirst(array $options = [])
    {
        $this->dispatchEvent('afterGetFirst', ['query' => $this->query, 'options' => $options]);
        $model = $this->query->first();
        $this->dispatchEvent('afterGetFirst', ['query' => $this->query, 'model' => $model, 'options' => $options]);

        $this->reset();

        if (is_null($model)) {
            throw new DataServiceNotFoundException(sprintf('%s not found.', class_basename($this->getModelClass())));
        }

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function get(array $options = [])
    {
        $this->dispatchEvent('beforeGet', ['query' => $this->query, 'options' => $options]);
        $models = $this->query->get();
        $this->dispatchEvent('afterGet', ['query' => $this->query, 'models' => $models, 'options' => $options]);

        $this->reset();

        return $models;
    }

    /**
     * @inheritdoc
     */
    public function count(array $options = []) : int
    {
        $this->dispatchEvent('beforeCount', ['query' => $this->query, 'options' => $options]);
        $count = $this->query->count();
        $this->dispatchEvent('afterCount', ['query' => $this->query, 'count' => $count, 'options' => $options]);

        $this->reset();

        return $count;
    }

    /**
     * @inheritdoc
     */
    public function save($model, array $attributes = [], array $options = [])
    {
        $this->assertModel($model);

        $model->fill($attributes);

        try {
            $this->dbConnection->beginTransaction();
            $this->dispatchEvent('beforeSave', ['model' => $model, 'attributes' => $attributes, 'options' => $options]);
            $model->save();
            $this->dispatchEvent('afterSave', ['model' => $model, 'attributes' => $attributes, 'options' => $options]);
            $this->dbConnection->commit();
        } catch (Throwable $e) {
            $this->dbConnection->rollBack();
            throw new DataServiceSavingException($e->getMessage());
        }
    }

    /**
     * @inheritdoc
     */
    public function delete($model, array $options = []) : bool
    {
        $this->assertModel($model);

        try {
            $this->dbConnection->beginTransaction();
            $this->dispatchEvent('beforeDelete', ['model' => $model, 'options' => $options]);
            $deleted = $model->delete();
            $this->dispatchEvent('afterDelete', ['model' => $model, 'deleted' => $deleted, 'options' => $options]);
            $this->dbConnection->commit();
        } catch (Throwable $e) {
            $this->dbConnection->rollBack();
            throw new DataServiceDeletingException($e->getMessage());
        }

        return $deleted;
    }
}
