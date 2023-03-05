<?php

namespace App\LaravelPermission\Models;
use App\LaravelPermission\Acl;
use Illuminate\Database\Query\Builder;

/**
 * Class Permission
 *
 * @package App\LaravelPermission\Models
 */
class Permission extends \Spatie\Permission\Models\Permission
{
    public $guard_name = 'api';

    /**
     * To exclude permission management from the list
     *
     * @param $query
     * @return Builder
     */
    public function scopeAllowed($query)
    {
        return $query->where('name', '!=', Acl::PERMISSION_PERMISSION_MANAGE);
    }
}
