<?php

namespace Proyecto404\UtilBundle\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class RepositoryHelper
 */
class RepositoryHelper
{
    /**
     * @param QueryBuilder $qb
     * @param QueryOptions $options
     * @param string       $defaultOrderBy
     * @param string       $defaultOrderDirection
     * @param array        $additionalAliases
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
            for ($i = 1; $i < count($options->orderBy); $i++) {
                $orderBy = self::replaceAliases($entityAlias, $additionalAliases, $options->orderBy[$i]);
                $qb->addOrderBy($orderBy, $options->orderDirection[$i]);
            }
        } else {
            $orderBy = self::replaceAliases($entityAlias, $additionalAliases, $options->orderBy);
            $qb->orderBy($orderBy, $options->orderDirection);
        }
    }

    /**
     * @param Query        $query
     * @param QueryOptions $options
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
