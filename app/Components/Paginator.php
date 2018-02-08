<?php

namespace App\Components;

use Illuminate\Pagination\Paginator as ParentPaginator;

class Paginator extends ParentPaginator
{

    private $action;

    /**
     * Get the URL for a given page number.
     *
     * @param  int  $page
     * @return string
     */
    public function url($page)
    {
        if ($this->action) {
            $parameters = [$this->pageName => $page];
            if (count($this->query) > 0) {
                $parameters = array_merge($this->query, $parameters);
            }

            return action($this->action, $parameters);
        } else {
            return parent::url($page);
        }
    }

    /**
     * 设置分页链接
     * @param $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

}