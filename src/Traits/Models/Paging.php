<?php


namespace Sczts\Skeleton\Traits\Models;


use Illuminate\Database\Eloquent\Builder;

trait Paging
{
    public function scopePaging(Builder $query, int $page,int $limit = 10)
    {
        return $query->skip(($page-1)*$limit)->take($limit);
    }
}
