<?php


namespace Sczts\Skeleton\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

trait Filter
{
    /**
     * @param Builder $query
     * @param Request $request
     * @return Builder
     * @throws \Exception
     */
    public function scopeFilter(Builder $query, Request $request)
    {
        return static::filter($query, $request);
    }

    /**
     * @param Builder $query
     * @param $where
     * @return Builder
     */
    public function scopeMultiplesWhere(Builder $query, $where)
    {
        return static::multiplesWhere($query, $where);
    }

    protected static function json2arr($value)
    {
        if (is_array($value)) {
            return $value;
        }
        return json_decode($value, true);
    }

    /**
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public static function filter(Builder $query, Request $request)
    {
        try {
            if ($request->has('where')) {
                $where = static::json2arr($request->input('where', []));
                $query = static::multiplesWhere($query, $where);
            }

            if ($request->has('search') && !empty($request->input('search'))) {
                $search = static::json2arr($request->input('search'));
                if (!empty($search['field'])) {
                    $query->where($search['field'], 'like', '%' . $search['value'] . '%');
                }
            }

            if ($request->has('orderBy')) {
                $order = explode(',', $request->input('orderBy', 'id,asc'));
                $query->orderBy(...$order);
            }
        } catch (QueryException  $exception) {
            throw $exception;
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param $where
     * @return Builder
     */
    public static function multiplesWhere(Builder $query, $where)
    {
        $where = static::json2arr($where);
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

