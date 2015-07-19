<?php

namespace Proyecto404\UtilBundle\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Helper class with utility methods for repositories.
 *
 * @author Nicolas Bottarini <nicolasbottarini@gmail.com>
 */
class RepositoryHelper
{
    /**
     * Configures the query builder object with the ordering options.
     *
     * Usage:
     * <code>
     *     $qb = $this->createQueryBuilder('s')
     *                ->leftJoin('s.area', 'a')
     *                ->leftJoin('a.city', 'c');
     *
     *     RepositoryHelper::applyOrderingOptionsToQueryBuilder(
     *         $qb,
     *         $options,
     *         'name',
     *         OrderDirections::ASCENDING,
     *         array('area' => 'a', 'area.city' => 'c'));
     * </code>
     *
     * @param QueryBuilder $qb                    The querybuilder
     * @param QueryOptions $options               The query options
     * @param string       $defaultOrderBy        Default order field
     * @param string       $defaultOrderDirection Default order direction
     * @param array        $additionalAliases     Query aditional aliases
     */
    public static function applyOrderingOptionsToQueryBuilder(
        QueryBuilder $qb,
        QueryOptions $options = null,
        $defaultOrderBy = '',
        $defaultOrderDirection = OrderDirections::DESCENDING,
        array $additionalAliases = array()
    ) {
        if ($options == null) {
            $options = new QueryOptions();
        }

        if (empty($options->orderBy) && $defaultOrderBy != '') {
            $options->orderBy = $defaultOrderBy;
            $options->orderDirection = $defaultOrderDirection;
        }

        $entityAlias = $qb->getRootAliases()[0];

        if (is_array($options->orderBy)) {
            $orderBy = self::replaceAliases($entityAlias, $additionalAliases, $options->orderBy[0]);
            $qb->orderBy($orderBy, $options->orderDirection[0]);
            for ($i = 1; $i < count($options->orderBy); ++$i) {
                $orderBy = self::replaceAliases($entityAlias, $additionalAliases, $options->orderBy[$i]);
                $qb->addOrderBy($orderBy, $options->orderDirection[$i]);
            }
        } else {
            $orderBy = self::replaceAliases($entityAlias, $additionalAliases, $options->orderBy);
            $qb->orderBy($orderBy, $options->orderDirection);
        }
    }

    /**
     * Applies the paging configuration to the query object.
     *
     * @param Query        $query   Query object
     * @param QueryOptions $options Paging options
     */
    public static function applyPagingOptionsToQuery(Query $query, QueryOptions $options = null)
    {
        if ($options == null) {
            return;
        }

        if (!empty($options->itemsPerPage)) {
            $query->setFirstResult(($options->page - 1) * $options->itemsPerPage);
            $query->setMaxResults($options->itemsPerPage);
        }
    }

    private static function replaceAliases($entityAlias, array $aliases, $orderBy)
    {
        $originalOrderBy = $orderBy;
        foreach ($aliases as $relation => $alias) {
            $orderBy = preg_replace('/^'.$relation.'\./', $alias.'.', $orderBy);
        }
        if ($originalOrderBy == $orderBy) {
            $orderBy = sprintf('%s.%s', $entityAlias, $orderBy);
        }

        return $orderBy;
    }
}
