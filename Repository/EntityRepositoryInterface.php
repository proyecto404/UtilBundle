<?php

namespace Proyecto404\UtilBundle\Repository;

use Doctrine\Common\Collections\Selectable;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Interface for domain entities repositories.
 *
 * Provides the interface for domain entities repositories
 *
 * @author Nicolas Bottarini <nicolasbottarini@gmail.com>
 */
interface EntityRepositoryInterface extends Selectable, ObjectRepository
{
    /**
     * Clears the repository, causing all managed entities to become detached.
     */
    public function clear();
}
