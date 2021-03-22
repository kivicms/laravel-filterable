<?php

namespace Kivicms\LaravelFilterable\Test\Stubs;

use Kivicms\LaravelFilterable\Generic\Filter;

class UserFilter extends Filter
{

    protected $filterables = [
        'id',
        'username',
        'email',
        'created_at',
        'updated_at',
        'deleted_at',
        'active',
        'published',
    ];


    /**Testing helper function.
     *
     * @return string
     */
    public function getGroupingOperator()
    {
        return $this->groupingOperator;
    }
}
