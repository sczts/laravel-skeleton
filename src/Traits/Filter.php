<?php


namespace Sczts\Skeleton\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

trait Filter
{
    protected function json2arr($value)
    {
        if (is_array($value)) {
            return $value;
        }
        return json_decode($value, true);
    }

    /**
     * 列表数据筛选
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function filter(Builder $query, Request $request)
    {
        try {
            if ($request->has('where')) {
                $query = $this->multiplesWhere($query, $request->input('where'));
            }

            if ($request->has('search') && !empty($request->input('search'))) {
                $search = $this->json2arr($request->input('search'));
                if (!empty($search['field'])) {
                    $query = $query->where($search['field'], 'like', '%' . $search['value'] . '%');
                }
            }

            if ($request->has('orderBy')) {
                $order = explode(',', $request->input('orderBy', 'id,asc'));
                $query = $query->orderBy(...$order);
            }
        } catch (QueryException  $exception) {
            throw $exception;
        }
        return $query;
    }

    /**
     * 多条件筛选
     * @param Builder $query
     * @param $where
     * @return Builder
     */
    public function multiplesWhere(Builder $query, $where)
    {
        $where = $this->json2arr($where);
        foreach ($where as $k => $v) {
            if (is_array($v)) {
                $query = $query->whereIn($k, $v);
            } else {
                $query = $query->where($k, $v);
            }
        }
        return $query;
    }
}

