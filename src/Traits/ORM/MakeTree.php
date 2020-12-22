<?php


namespace Sczts\Skeleton\Traits\ORM;


use Illuminate\Support\Arr;

trait MakeTree
{
    public static function getIdColumn(): string
    {
        return 'id';
    }

    public static function getPidColumn(): string
    {
        return 'pid';
    }

    /**
     * 构造树形结构
     * @param \Illuminate\Database\Eloquent\Collection $collection
     * @param int $pid
     * @param $children_field
     * @param $withCount
     * @return mixed
     */
    public static function makeTree($collection, $pid = 0, $children_field = 'children', $withCount = false)
    {
        $pid_column = static::getPidColumn();
        $groups = $collection->groupBy($pid_column);
        $tree = Arr::get($groups, $pid, []);
        foreach ($tree as $index => $node) {
            $tree[$index] = static::makeNode($node, $groups, $children_field, $withCount);
        };
        return $tree;
    }

    /**
     * 递归构造任务树节点
     * @param $node
     * @param $groups
     * @param $children_field
     * @param $withCount
     * @return mixed
     */
    public static function makeNode($node, $groups, $children_field = 'children', $withCount = false)
    {
        $id_column = static::getIdColumn();
        if ($groups->has($node[$id_column])) {
            $children = $groups[$node[$id_column]]->toArray();
            foreach ($children as $index => $item) {
                $children[$index] = static::makeNode($item, $groups, $children_field, $withCount);
            }
            $node[$children_field] = $children;
            if (is_string($withCount)) {
                $node[$withCount] = count($children);
            } elseif (is_bool($withCount) && $withCount == true) {
                $node[$children_field . '_count'] = count($children);
            }
        }
        return $node;
    }
}