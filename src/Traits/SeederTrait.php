<?php


namespace Sczts\Skeleton\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

trait SeederTrait
{
    public function createParentChild(Builder $query, $data,$pid = 0, $children_column  = 'children', $pid_column = 'pid')
    {
        foreach ($data as $item) {
            $item[$pid_column] = $pid;
            $children = Arr::pull($item,$children_column);
            $model = $query->create($item);
            if (!empty($children)){
                $this->createParentChild($query,$children,$model->id,$children_column,$pid_column);
            }
        }
    }
}
