<?php


namespace Sczts\Skeleton\Traits\Models;


trait HasChildren
{
    public function children()
    {
        return $this->hasMany(self::class,'pid');
    }
}
