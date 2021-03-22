<?php

namespace Kivicms\LaravelFilterable\Test\Stubs;

use Kivicms\LaravelFilterable\Filter;

class PermissionType extends Filter
{

    protected $filterables = [
        'id',
        'permission_id',
        'type',
        'created_at',
        'updated_at',
        'deleted_at',
        'active',
        'published',
    ];


    function filterMap(): array
    {
        return [];
    }
}
