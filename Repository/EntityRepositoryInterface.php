<?php
namespace Proyecto404\UtilBundle\Repository;

use Doctrine\Common\Collections\Selectable;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Interface EntityRepositoryInterface
 */
interface EntityRepositoryInterface extends Selectable, ObjectRepository
{
    /**
     * Clears the repository, causing all managed entities to become detached.
     *
     * @return void
     */
    public function clear();
}
