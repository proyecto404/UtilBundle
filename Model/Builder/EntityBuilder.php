<?php

namespace Proyecto404\UtilBundle\Model\Builder;

/**
 * Class EntityBuilder
 */
abstract class EntityBuilder
{
    protected $entity;

    /**
     * @param mixed $entity
     */
    public function __construct($entity = null)
    {
        $this->entity = $entity;
    }

    /**
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
     * @return mixed|null
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return bool
     */
    abstract public function isNew();

    /**
     * @return mixed
     */
    abstract protected function createEntity();

    /**
     * @param mixed $entity
     *
     * @return void
     */
    abstract protected function updateEntity($entity);
}
