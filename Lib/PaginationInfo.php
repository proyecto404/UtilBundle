<?php

namespace Proyecto404\UtilBundle\Lib;

/**
 * Class PaginationInfo
 */
class PaginationInfo
{
    private $page;
    private $totalResultsCount;
    private $itemsPerPage;

    /**
     * @param int $page
     * @param int $totalResultsCount
     * @param int $itemsPerPage
     */
    public function __construct($page, $totalResultsCount, $itemsPerPage)
    {
        $this->page = $page;
        $this->totalResultsCount = $totalResultsCount;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getTotalResultsCount()
    {
        return $this->totalResultsCount;
    }

    /**
     * @return int
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * @return int
     */
    public function getPageCount()
    {
        return ceil($this->getTotalResultsCount() / $this->getItemsPerPage());
    }

    /**
     * @return bool
     */
    public function shouldPaginate()
    {
        return $this->getTotalResultsCount() > $this->getItemsPerPage();
    }
}
