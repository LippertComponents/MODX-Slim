<?php
/**
 * Created by PhpStorm.
 * User: joshgulledge
 */

namespace LCI\MODX\Slim\Helpers;

trait PaginationHelper
{
    /** @var int  */
    protected $page = 1;

    /** @var int  */
    protected $limit = 25;

    /** @var string  */
    protected $sort_column = 'modResource.publishedon';

    /** @var string  */
    protected $sort_dir = 'DESC';

    /** @var array  */
    protected $pagination_data = [];

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getSortColumn()
    {
        return $this->sort_column;
    }

    /**
     * @return string
     */
    public function getSortDir()
    {
        return $this->sort_dir;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param int $limit
     * This is an alias for setLimit
     * @return $this
     */
    public function setPerPageLimit(int $limit)
    {
        return $this->setLimit($limit);
    }

    /**
     * @param int $page
     * Start at 1 and increment by 1
     * @return $this
     */
    public function setPage(int $page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @param string $sort_column
     * @return $this
     */
    public function setSortColumn($sort_column)
    {
        $this->sort_column = $sort_column;
        return $this;
    }

    /**
     * @param string $sort_dir
     * @return PaginationHelper
     */
    public function setSortDir($sort_dir)
    {
        $this->sort_dir = $sort_dir;
        return $this;
    }

    /**
     * @param \xPDOQuery $query
     * Only works with xPDO queries 
     * @return \xPDOQuery
     */
    protected function addPagination(\xPDOQuery $query)
    {
        $query->sortBy($this->getSortColumn(), $this->getSortDir());

        $offset = $this->getOffset();

        $total = $this->modx->getCount($query->getClass(), $query);

        $this->pagination_data = [
            'total_count' => $total,
            'current_page' => $this->getPage(),
            'total_pages' => ceil($total / $this->getLimit()),
            'limit' => $this->getLimit()
        ];

        $query->limit($this->getLimit(), ($offset < 0 ? 0 : $offset * $this->getLimit()) );

        return $query;
    }

    /**
     * @return int
     */
    protected function getOffset()
    {
        return $this->getPage() - 1;
    }

    /**
     * @return array
     */
    public function getPaginationData()
    {
        return $this->pagination_data;
    }
}