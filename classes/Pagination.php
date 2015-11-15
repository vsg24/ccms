<?php

class Pagination
{
    public $current_page;
    public $per_page;
    public $total_count;

    function __construct($max_pp = null, $count = null)
    {
        $posts = new Posts();
        $this->current_page = $posts->getCurrentPageNumber();
        $this->per_page = $posts->getMaxPostPerPage();
        $this->total_count = $posts->getPostsCount();
        if(isset($count))
        {
            $this->total_count = $count;
        }
        if(isset($max_pp))
        {
            $this->per_page = $max_pp;
        }
    }

    public function totalPages()
    {
        return ceil($this->total_count/$this->per_page);
    }

    public function previousPage()
    {
        return $this->current_page - 1;
    }

    public function nextPage()
    {
        return $this->current_page + 1;
    }

    public function hasPreviousPage()
    {
        return $this->previousPage() >= 1 ? true : false;
    }

    public function hasNextPage()
    {
        return $this->nextPage() <= $this->totalPages() ? true : false;
    }

    public function offset()
    {
        return ($this->current_page - 1) * $this->per_page;
    }
}