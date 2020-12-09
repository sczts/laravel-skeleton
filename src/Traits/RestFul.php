<?php

namespace Sczts\Skeleton\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


trait RestFul
{
    use Filter, JsonResponse;

    /**
     * 获取列表
     * @param Request $request
     * @return mixed
     */
    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $query = $this->filter($this->getModel(), $request);
        $count = $query->count();
        $data = $query->skip(($page - 1) * $limit)->take($limit)->get();
        return $this->withCount($data, $count);
    }

    /**
     * 获取资源
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data = $this->getModel()->findOrFail($id);
        return $this->success($data);
    }

    /**
     * 创建资源
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->addRule());
        $status = $this->getModel()->create($data);
        if ($status) {
            return $this->success();
        }
        return $this->failed();
    }

    /**
     * 修改资源
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $data = $request->validate($this->editRule());
        $status = $this->getModel()->findOrFail($id)->update($data);
        if ($status) {
            return $this->success();
        }
        return $this->failed();
    }

    /**
     * 删除资源
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $status = $this->getModel()->where('id', $id)->delete();
        if ($status) {
            return $this->success();
        }
        return $this->failed();
    }

    protected abstract function getModel(): Builder;

    protected abstract function addRule(): array;

    protected abstract function editRule(): array;


}
