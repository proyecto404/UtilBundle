<?php

namespace Proyecto404\UtilBundle\Model\Builder;

/**
 * Base class for entity builders.
 *
 * An entity builder constructs or updates a domain entity exposing an interface
 * with the properties of that entity.
 *
 * @author Nicolas Bottarini <nicolasbottarini@gmail.com>
 */
abstract class EntityBuilder
{
    protected $entity;

    /**
     * Constructor.
     *
     * @param mixed $entity The entity object
     */
    public function __construct($entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * Builds a new entity or updates the given entity.
     *
     * @return mixed|null
     */
    public function build()
    {
        if ($this->isNew()) {
            $this->entity = $this->createEntity();
        } else {
            $this->updateEntity($this->entity);
        }

        return $this->entity;
    }

    /**
     * Returns the entity object.
     *
     * @return mixed|null
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Indicates if the entity is beign created or updated.
     *
     * @return bool
     */
    abstract public function isNew();

    /**
     * Creates the new entity with the builder data.
     *
     * @return mixed
     */
    abstract protected function createEntity();

    /**
     * Updates the entity with the builder data.
     *
     * @param mixed $entity
     */
    abstract protected function updateEntity($entity);
}
