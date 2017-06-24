<?php

namespace Lib\DataProvider;

use Closure;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionInterface as Connection;
use Illuminate\Database\Eloquent\Model;
use Lib\DataProvider\Contracts\Criteria;
use Lib\DataProvider\Contracts\DataProvider as DataProviderContract;
use Lib\DataProvider\Exceptions\DataProviderDeletingException;
use Lib\DataProvider\Exceptions\DataProviderException;
use Lib\DataProvider\Exceptions\DataProviderNotFoundException;
use Lib\DataProvider\Exceptions\DataProviderSavingException;
use Throwable;

/**
 * Class DataProvider.
 *
 * @package Lib\DataProvider
 */
abstract class DataProvider implements DataProviderContract
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
     * Database connection class instance.
     *
     * @var mixed
     */
    protected $dbConnection;

    /**
     * Events dispatcher class instance.
     *
     * @var mixed
     */
    protected $events;

    /**
     * DataProvider constructor.
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
     * @throws DataProviderException
     */
    protected function resetModel()
    {
        $modelClass = $this->getModelClass();

        if (!class_exists($modelClass)) {
            throw new DataProviderException(sprintf('The %s class does not exist.', $modelClass));
        }

        if (!is_subclass_of($modelClass, Model::class)) {
            throw new DataProviderException(sprintf('The model must be inherited from the %s class.', Model::class));
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
     * @throws DataProviderException
     */
    protected function assertModel($model)
    {
        $modelClass = $this->getModelClass();

        if (!($model instanceof $modelClass)) {
            throw new DataProviderException(sprintf('The model must be an instance of the %s class.', $modelClass));
        }
    }

    /**
     * Dispatch an event with name 'NameSpace\Path\To\DataProvider@eventName'.
     *
     * @param string $eventName
     * @param array $data
     * @return void
     */
    protected function dispatchEvent(string $eventName, &...$data)
    {
        $this->events->dispatch(static::class . '@' . $eventName, $data);
    }

    /**
     * @inheritdoc
     */
    abstract public function getModelClass(): string;

    /**
     * @inheritdoc
     */
    public function applyCriteria(Criteria $criteria)
    {
        $criteria->apply($this->query);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function applyCriteriaWhen(bool $value, Criteria $criteria)
    {
        if ($value) {
            $this->applyCriteria($criteria);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getById($id, array $options = [])
    {
        $this->dispatchEvent('beforeGetById', $this->query, $options);
        $model = $this->query->find($id);
        if (is_null($model)) {
            throw new DataProviderNotFoundException(sprintf('%s not found.', class_basename($this->getModelClass())));
        }
        $this->dispatchEvent('afterGetById', $model, $options);
        $this->reset();

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function getFirst(array $options = [])
    {
        $this->dispatchEvent('beforeGetFirst', $this->query, $options);
        $model = $this->query->first();
        if (is_null($model)) {
            throw new DataProviderNotFoundException(sprintf('%s not found.', class_basename($this->getModelClass())));
        }
        $this->dispatchEvent('afterGetFirst', $model, $options);
        $this->reset();

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function get(array $options = [])
    {
        $this->dispatchEvent('beforeGet', $this->query, $options);
        $models = $this->query->get();
        $this->dispatchEvent('afterGet', $models, $options);
        $this->reset();

        return $models;
    }

    /**
     * @inheritdoc
     */
    public function exists(array $options = []): bool
    {
        $this->dispatchEvent('beforeExists', $this->query, $options);
        $exists = $this->query->exists();
        $this->dispatchEvent('afterExists', $exists, $options);
        $this->reset();

        return $exists;
    }

    /**
     * @inheritdoc
     */
    public function each(Closure $callback, int $chunkSize = 100, array $options = [])
    {
        $this->query->chunk($chunkSize, function ($models) use ($callback, $options) {
            foreach ($models as $model) {
                $this->dispatchEvent('beforeEach', $this->query, $options);
                $callback($model);
                $this->dispatchEvent('afterEach', $model, $options);
            }
        });

        $this->reset();
    }

    /**
     * @inheritdoc
     */
    public function paginate(int $page = 1, int $perPage = 20, array $options = [])
    {
        $this->dispatchEvent('beforePaginate', $this->query, $options);
        $models = $this->query->paginate($perPage, ['*'], 'page', $page);
        $this->dispatchEvent('afterPaginate', $models, $options);
        $this->reset();

        return $models;
    }

    /**
     * @inheritdoc
     */
    public function count(array $options = []): int
    {
        $this->dispatchEvent('beforeCount', $this->query, $options);
        $count = $this->query->count();
        $this->dispatchEvent('afterCount', $count, $options);
        $this->reset();

        return $count;
    }

    /**
     * @inheritdoc
     */
    public function save($model, array $attributes = [], array $options = [])
    {
        $this->assertModel($model);

        try {
            $this->dbConnection->beginTransaction();
            $this->dispatchEvent('beforeSave', $model, $attributes, $options);
            $model->fill($attributes);
            $model->save();
            $this->dispatchEvent('afterSave', $model, $attributes, $options);
            $this->dbConnection->commit();
        } catch (Throwable $e) {
            $this->dbConnection->rollBack();
            throw new DataProviderSavingException($e->getMessage(), $e->getCode(), $e);
        } finally {
            $this->reset();
        }
    }

    /**
     * @inheritdoc
     */
    public function delete($model, array $options = []): bool
    {
        $this->assertModel($model);

        try {
            $this->dbConnection->beginTransaction();
            $this->dispatchEvent('beforeDelete', $model, $options);
            $deleted = $model->delete();
            $this->dispatchEvent('afterDelete', $model, $deleted, $options);
            $this->dbConnection->commit();
        } catch (Throwable $e) {
            $this->dbConnection->rollBack();
            throw new DataProviderDeletingException($e->getMessage(), $e->getCode(), $e);
        } finally {
            $this->reset();
        }

        return $deleted ?? false;
    }
}
