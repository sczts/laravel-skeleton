<?php


namespace Sczts\Skeleton\Traits\ORM;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait ClosureTable
{
    use HasChildren;

    // 关联表名称
    abstract public static function tableName();

    // 主表查询构造器
    abstract public static function master(): Builder;

    // 父节点字段
    public static function getRootColumn(): string
    {
        return 'root_id';
    }

    // 节点字段
    public static function getNodeColumn(): string
    {
        return 'node_id';
    }

    // 关联关系深度
    public static function getDepthColumn(): string
    {
        return 'depth';
    }

    /**
     * 新增节点
     * @param $node_id
     * @param $root_id
     */
    public static function insert($node_id, $root_id)
    {
        $table = static::tableName();
        $node_column = static::getNodeColumn();
        $root_column = static::getRootColumn();
        $depth_column = static::getDepthColumn();

        // 查询当前节点所有的父节点id 加上当前节点与自身的关系
        $sql = "INSERT INTO {$table} ({$root_column}, {$node_column}, {$depth_column})
            SELECT {$root_column},{$node_id} as {$node_column},{$depth_column} + 1 as {$depth_column} FROM {$table} WHERE {$node_column} = {$root_id}
            UNION ALL
            SELECT {$node_id}, {$node_id}, 0";
        DB::statement($sql);
    }

    /**
     * 删除节点
     * @param $node_id
     */
    public static function remove($node_id)
    {
        $table = static::tableName();
        $node_column = static::getNodeColumn();
        $root_column = static::getRootColumn();

        // 删除当前节点及所有子节点的关系
        $remove = "DELETE FROM {$table}
            WHERE {$node_column} IN (
                SELECT a FROM (
                    SELECT {$node_column} AS a FROM {$table}
                    WHERE {$root_column} = {$node_id}
                ) as ct
            )";
        DB::statement($remove, ['{$node_column}' => $node_id]);
    }

    /**
     * 解除节点关系
     * @param $node_id
     */
    public static function unbind($node_id)
    {
        $table = static::tableName();
        $node_column = static::getNodeColumn();
        $root_column = static::getRootColumn();

        // 移除当前节点及节点下所有子节点与当前节点所有父节点的关系
        // 保留子节点与孙子节点的关联关系
        $unbind = "DELETE FROM {$table}
            WHERE {$node_column} IN (
              SELECT d FROM (
                SELECT {$node_column} as d FROM {$table}
                WHERE {$root_column} = {$node_id}
              ) as dct
            )
            AND {$root_column} IN (
              SELECT a FROM (
                SELECT {$root_column} AS a FROM {$table}
                WHERE {$node_column} = {$node_id}
                AND {$root_column} <> {$node_id}
              ) as ct
            )";
        DB::statement($unbind);
    }

    /**
     * 移动节点
     * @param $node_id // 要移动的节点id
     * @param $root_id // 要移动到的父节点id
     */
    public static function move($node_id, $root_id)
    {
        $table = static::tableName();
        $node_column = static::getNodeColumn();
        $root_column = static::getRootColumn();
        $depth_column = static::getDepthColumn();

        static::unbind($node_id);
        // 通过 CROSS JOIN 计算 $root_id 的所有父节点关系与 $node_id 所有子集关系（包含自身）的笛卡尔积，合并 depth 值并 +1
        // 笛卡尔积可通过代码计算，减少数据库运算
        $move = "INSERT INTO {$table} ({$root_column}, {$node_column}, {$depth_column})
            SELECT supertbl.{$root_column}, subtbl.{$node_column}, supertbl.{$depth_column}+subtbl.{$depth_column}+1
            FROM {$table} as supertbl
            CROSS JOIN {$table} as subtbl
            WHERE supertbl.{$node_column} = {$root_id}
            AND subtbl.{$root_column} = {$node_id}
        ";
        DB::statement($move);
    }

    /**
     * 获取节点/多个节点的所有后代节点
     * @param int|array $id 单个节点或多个节点数据
     * @param null $depth 层级深度 null:获取所有子节点  n:获取第n级子节点数据
     * @return \Illuminate\Support\Collection
     */
    public static function descendants($id, $depth = null)
    {
        $root_column = static::getRootColumn();
        $depth_column = static::getDepthColumn();
        if (is_array($id)) {
            $query = static::query()->whereIn($root_column, $id);
        } else {
            $query = static::query()->where($root_column, $id);
        }

        if ($depth) {
            $query = $query->where($depth_column, $depth);
        } else {
            $query = $query->where($depth_column, '<>', 0);
        }
        return $query->get();
    }

    /**
     * 获取节点/多个节点的所有后代节点id
     * @param int|array $id
     * @param null $depth
     * @return Collection
     */
    public static function descendantIds($id, $depth = null): Collection
    {
        return static::descendants($id, $depth)->pluck(static::getNodeColumn());
    }

    /**
     * 获取节点/多个节点的所有祖先节点
     * @param int|array $id
     * @return \Illuminate\Support\Collection
     */
    public static function ancestors($id)
    {
        $node_column = static::getNodeColumn();
        $depth_column = static::getDepthColumn();
        $query = static::query()->distinct();
        if (is_array($id)) {
            $query = $query->whereIn($node_column, $id);
        } else {
            $query = $query->where($node_column, $id);
        }
        return $query->where($depth_column, '<>', 0)->get()->sortBy(static::getDepthColumn());
    }

    /**
     * 获取节点/多个节点的所有祖先节点id
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public static function ancestorIds($id)
    {
        return static::ancestors($id)->pluck(static::getRootColumn());
    }

    /**
     * 重建数据关系
     * @param int $root_id
     */
    public static function rebuildRelation()
    {
        static::query()->truncate();
        static::buildRelation(0);
    }

    /**
     * 构建数据关系
     * @param $pid
     */
    public static function buildRelation($pid)
    {
        $id_column = self::getIdColumn();
        $pid_column = self::getPidColumn();
        static::master()->where($pid_column, $pid)->chunkById(100, function ($items) use ($id_column, $pid_column) {
            foreach ($items as $item) {
                static::insert($item->$id_column, $item->$pid_column);
                static::buildRelation($item->$id_column);
            }
        }, $id_column);
    }
}
