<?php


namespace Sczts\Skeleton\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

trait SeederTrait
{
    /**
     * 递归创建子数据
     * @param Builder $query
     * @param $data
     * @param int $pid
     * @param string $children_column
     * @param string $pid_column
     */
    public function recursiveCreate(Builder $query, $data, $pid = 0, $children_column = 'children', $pid_column = 'pid')
    {
        foreach ($data as $item) {
            $item[$pid_column] = $pid;
            $children = Arr::pull($item, $children_column);
            $model = $query->create($item);
            if (!empty($children)) {
                $this->recursiveCreate($query, $children, $model->id, $children_column, $pid_column);
            }
        }
    }
}
