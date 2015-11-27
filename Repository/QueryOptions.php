<?php

namespace Proyecto404\UtilBundle\Repository;

/**
 * Query options.
 *
 * @author Nicolas Bottarini <nicolasbottarini@gmail.com>
 */
class QueryOptions
{
    /**
     * @var int
     */
    public $page;

    /**
     * @var int
     */
    public $itemsPerPage;

    /**
     * @var array|string
     */
    public $orderBy;

    /**
     * @var array|string
     */
    public $orderDirection;

    /**
     * @var array
     */
    public $fetchRelations;

    /**
     * @param int        $page
     * @param int|null   $itemsPerPage
     * @param string     $orderBy
     * @param string     $orderDirection
     * @param array|null $fetchRelations
     */
    public function __construct(
        $page = 1,
        $itemsPerPage = null,
        $orderBy = '',
        $orderDirection = OrderDirections::DESCENDING,
        array $fetchRelations = null
    ) {
        $this->page = $page;
        $this->itemsPerPage = $itemsPerPage;
        $this->orderBy = $orderBy;
        $this->orderDirection = $orderDirection;
        $this->fetchRelations = $fetchRelations;
    }

    /**
     * @param string $defaultOrderBy
     * @param string $defaultOrderDirection
     *
     * @return QueryOptions
     */
    public function defaultOrder($defaultOrderBy = '', $defaultOrderDirection = OrderDirections::DESCENDING)
    {
        if ($this->orderBy == '' && $defaultOrderBy != '') {
            $this->orderBy = $defaultOrderBy;
            $this->orderDirection = $defaultOrderDirection;
        }

        return $this;
    }

    /**
     * @param array|null $fetchRelations
     *
     * @return QueryOptions
     */
    public function defaultFetch(array $fetchRelations = null)
    {
        if ($this->fetchRelations == null) {
            $this->fetchRelations = $fetchRelations;
        }

        return $this;
    }

    /**
     * @param QueryOptions|null $options
     *
     * @return QueryOptions
     */
    public static function from(
        QueryOptions $options = null
    ) {
        if ($options == null) {
            $options = new QueryOptions();
        }

        return $options;
    }
}
