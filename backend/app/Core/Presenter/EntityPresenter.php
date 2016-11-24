<?php

namespace App\Core\Presenter;

use JsonSerializable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Class EntityPresenter
 * @package App\Presenters
 */
abstract class EntityPresenter implements Arrayable, Jsonable, JsonSerializable
{
    /**
     * @var mixed
     */
    protected $entity;

    /**
     * EntityPresenter constructor.
     *
     * @param mixed $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * Override this method to build properties map.
     *
     * Returns the array map of presenter properties mapped to entity properties.
     *
     * Example: return [
     *     ...
     *     'some_presenter_property' => 'some_entity_property',
     *     ...
     * ]
     *
     * Note: You can override presenter property value by creating method
     * with the same name as the property name.
     *
     * @return array
     */
    protected function map()
    {
        return [];
    }

    /**
     * Get original entity object.
     *
     * @return mixed
     */
    public function getOriginalEntity()
    {
        return $this->entity;
    }

    /**
     * Magical getter for mapping presenter properties to entity properties.
     *
     * @param mixed $propertyName
     * @return mixed
     */
    public function __get(string $propertyName)
    {
        $methodName = $propertyName;
        if (method_exists($this, $methodName)) {
            return $this->{$methodName}();
        }

        $methodName = str_replace('_', '', $propertyName);
        if (method_exists($this, $methodName)) {
            return $this->{$methodName}();
        }

        if (key_exists($propertyName, $this->map())) {
            $property = $this->map()[$propertyName];
            return $this->entity->{$property};
        }

        return null;
    }

    /**
     * Convert the model to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $array = [];

        $properties = array_keys($this->map());
        foreach ($properties as $property) {
            $array[$property] = $this->{$property};
        }

        return $array;
    }

    /**
     * @inheritdoc
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
