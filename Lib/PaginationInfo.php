<?php

namespace Proyecto404\UtilBundle\Lib;

/**
 * Represents pagination information of set of data.
 *
 * @author Nicolas Bottarini <nicolasbottarini@gmail.com>
 */
class PaginationInfo
{
    private $page;
    private $totalItemCount;
    private $itemsPerPage;

    /**
     * Constructor.
     *
     * @param int $page           The current page (1 indexed)
     * @param int $totalItemCount The total amount of items
     * @param int $itemsPerPage   The amount of items per page
     */
    public function __construct($page, $totalItemCount, $itemsPerPage)
    {
        $this->page = $page;
        $this->totalItemCount = $totalItemCount;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * Gets the current page (1 indexed).
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Gets total item count.
     *
     * @return int
     */
    public function getTotalItemCount()
    {
        return $this->totalItemCount;
    }

    /**
     * Gets items per page.
     *
     * @return int
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * Gets the page count.
     *
     * @return int
     */
    public function getPageCount()
    {
        return ceil($this->getTotalItemCount() / $this->getItemsPerPage());
    }

    /**
     * Indicates if the amount of items should be paginated.
     *
     * @return bool
     */
    public function shouldPaginate()
    {
        return $this->getTotalItemCount() > $this->getItemsPerPage();
    }
}
