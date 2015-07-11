<?php

namespace Proyecto404\UtilBundle\Repository;

/**
 * Class QueryOptions
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
     * @param int    $page
     * @param int    $itemsPerPage
     * @param string $orderBy
     * @param string $orderDirection
     */
    public function __construct($page = 1, $itemsPerPage = 10, $orderBy = '', $orderDirection = OrderDirections::DESCENDING)
    {
        $this->page = $page;
        $this->itemsPerPage = $itemsPerPage;
        $this->orderBy = $orderBy;
        $this->orderDirection = $orderDirection;
    }
}
