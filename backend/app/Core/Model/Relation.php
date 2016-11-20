<?php

namespace App\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Trait Relation
 * @package App\Core\Model
 */
trait Relation
{
    /**
     * Save the model with relations to the database using transaction.
     *
     * @param array $attributes
     * @param array $relations
     * @param bool $overrideRelations
     * @throws Throwable
     */
    public function saveWithRelationsOrFail(array $attributes, array $relations = [], bool $overrideRelations = false)
    {
        /** @var Model $this */

        $this->fill($attributes);
        DB::beginTransaction();
        try {
            $this->saveOrFail();
            foreach ($relations as $relationName) {
                if (method_exists($this, $relationName)) {
                    $relation = $this->{$relationName}();
                    if ($relation instanceof BelongsToMany || $relation instanceof HasMany) {
                        $overrideRelations ? $relation->delete() && $relation->detach() : null;
                        $relation->createMany($attributes[$relationName]);
                    } elseif ($relation instanceof HasOne) {
                        $overrideRelations ? $relation->delete() : null;
                        $relation->create($attributes[$relationName]);
                    }
                }
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete the model with relations from the database using transaction.
     *
     * @param array $relations
     * @return bool|null
     * @throws Throwable
     */
    public function deleteWithRelationsOrFail(array $relations = null)
    {
        /** @var Model $this */

        DB::beginTransaction();
        try {
            foreach ($this->getRelations() as $relationName => $relationCollection) {
                if ($relations === null || in_array($relationName, $relations)) {
                    if (method_exists($this, $relationName)) {
                        $relation = $this->{$relationName}();
                        if ($relation instanceof BelongsToMany || $relation instanceof HasMany) {
                            $relation->delete();
                            $relation->detach();
                        } elseif ($relation instanceof HasOne) {
                            $relation->delete();
                        }
                    }
                }
            }
            $result = $this->delete();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return $result;
    }
}
