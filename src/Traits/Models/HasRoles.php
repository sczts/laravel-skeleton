<?php


namespace Sczts\Skeleton\Traits\Models;


trait HasRoles
{
    public function roles()
    {
        return $this->belongsToMany('', '', 'role_id', 'permission_id');
    }
}
