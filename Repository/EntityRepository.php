<?php

namespace Proyecto404\UtilBundle\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class EntityRepository
 */
abstract class EntityRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @var array
     */
    protected $aliases;
    /**
     * @var array
     */
    protected $processedAliases;

    /**
     * {@inheritdoc}
     */
    public function __construct($em, ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $this->configureAliases();
        $this->processAliases();
    }

    /**
     * Return root entity alias.
     *
     * @return string
     */
    protected function rootAlias()
    {
        return $this->getAliasFor('');
    }

    /**
     * Return relation alias
     *
     * For example: getAliasFor('order.user') => 'u'
     *
     * @param string $relation
     *
     * @return string
     */
    protected function getAliasFor($relation)
    {
        if (!array_key_exists($relation, $this->aliases)) {
            throw new \RuntimeException('Relation alias not found:'.$relation);
        }

        return $this->aliases[$relation];
    }

    /**
     * @param QueryBuilder $qb
     * @param mixed        $criteria
     */
    protected function applySearchCriteriaToQueryBuilder(QueryBuilder $qb, $criteria)
    {
    }

    /**
     * Add default relations into query builder (do LEFT JOINs)
     *
     * @param QueryBuilder $qb
     */
    protected function addDefaultRelations(QueryBuilder $qb)
    {
        $defaultLoadedRelations = $this->defaultLoadedRelations();
        foreach ($defaultLoadedRelations as $relation) {
            $qb->leftJoin($this->processedAliases[$relation], $this->getAliasFor($relation));
        }
    }

    /**
     * Add relations into query builder (do LEFT JOINs)
     *
     * @param QueryBuilder $qb
     * @param array        $relations
     */
    protected function addRelations(QueryBuilder $qb, array $relations)
    {
        $defaultLoadedRelations = $this->defaultLoadedRelations();
        foreach ($relations as $relation) {
            if (!in_array($relation, $defaultLoadedRelations)) {
                $qb->leftJoin($this->processedAliases[$relation], $this->getAliasFor($relation));
            }
        }
    }

    /**
     * Specify relations to load by default
     *
     * @return array
     */
    protected function defaultLoadedRelations()
    {
        return array();
    }

    /**
     * Configures the query builder object with the ordering options.
     *
     * @param QueryBuilder $qb                The querybuilder
     * @param QueryOptions $options           The query options
     */
    protected function applyOrderingOptionsToQueryBuilder(QueryBuilder $qb, QueryOptions $options = null)
    {
        $options = QueryOptions::from($options);

        if (is_array($options->orderBy)) {
            $orderBy = $this->replaceAliases($options->orderBy[0]);
            $qb->orderBy($orderBy, $options->orderDirection[0]);
            for ($i = 1; $i < count($options->orderBy); ++$i) {
                $orderBy = $this->replaceAliases($options->orderBy[$i]);
                $qb->addOrderBy($orderBy, $options->orderDirection[$i]);
            }
        } else {
            $orderBy = $this->replaceAliases($options->orderBy);
            $qb->orderBy($orderBy, $options->orderDirection);
        }
    }

    /**
     * Applies the paging configuration to the query object.
     *
     * @param Query        $query   Query object
     * @param QueryOptions $options Paging options
     */
    protected function applyPagingOptionsToQuery(Query $query, QueryOptions $options = null)
    {
        if ($options == null) {
            return;
        }

        if (!empty($options->itemsPerPage)) {
            $query->setFirstResult(($options->page - 1) * $options->itemsPerPage);
            $query->setMaxResults($options->itemsPerPage);
        }
    }

    /**
     * @param mixed       $criteria
     * @param string|null $rootAlias
     *
     * @return mixed
     */
    protected function countQuery($criteria = null, $rootAlias = null)
    {
        if ($rootAlias == null) {
            $rootAlias = $this->rootAlias();
        }

        $qb = $this->createQueryBuilder($rootAlias);
        $qb->select('count('.$rootAlias.'.id)');
        $this->addDefaultRelations($qb);

        if ($criteria) {
            $this->applySearchCriteriaToQueryBuilder($qb, $criteria);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param mixed             $criteria
     * @param QueryOptions|null $options
     * @param string|null       $rootAlias
     *
     * @return mixed
     */
    protected function searchQuery($criteria = null, QueryOptions $options = null, $rootAlias = null)
    {
        if ($rootAlias == null) {
            $rootAlias = $this->rootAlias();
        }

        $qb = $this->createQueryBuilder($rootAlias);

        if ($options->fetchRelations != null) {
            $this->addRelations($qb, $options->fetchRelations);
            $qb->select($this->rootAlias());
            foreach ($options->fetchRelations as $relation) {
                $qb->addSelect($this->getAliasFor($relation));
            }
        }

        $this->addDefaultRelations($qb);
        if ($criteria) {
            $this->applySearchCriteriaToQueryBuilder($qb, $criteria);
        }
        $this->applyOrderingOptionsToQueryBuilder($qb, $options);

        $query = $qb->getQuery();

        $this->applyPagingOptionsToQuery($query, $options);

        return $query->execute();
    }

    protected function configureAliases()
    {
        $this->aliases = array(
            '' => 'e' // Root entity
        );
    }

    private function replaceAliases($orderBy)
    {
        $originalOrderBy = $orderBy;
        foreach ($this->aliases as $relation => $alias) {
            $orderBy = preg_replace('/^'.$relation.'\./', $alias.'.', $orderBy);
        }
        if ($originalOrderBy == $orderBy) {
            $orderBy = sprintf('%s.%s', $this->rootAlias(), $orderBy);
        }

        return $orderBy;
    }

    private function processAliases()
    {
        $root = $this->rootAlias();
        $this->processedAliases = array();
        foreach ($this->aliases as $relation => $alias) {
            if ($relation == '') {
                continue;
            }

            if (!preg_match('/^(.+)\.(\w+)/', $relation, $matches)) {
                $this->processedAliases[$relation] = $root.'.'.$relation;
                continue;
            }

            $this->processedAliases[$relation] = $this->getAliasFor($matches[1]).'.'.$matches[2];
        }
    }
}
