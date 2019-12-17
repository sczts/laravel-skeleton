<?php

namespace Sczts\Skeleton\Traits;


use Illuminate\Database\Eloquent\Builder;
use Sczts\Skeleton\Http\StatusCode;
use Sczts\Skeleton\Traits\Models\Filter;
use Illuminate\Http\Request;


trait RestFul
{
    use Filter;

    /**
     * 获取列表
     * @param Request $request
     * @return mixed
     */
    public function list(Request $request)
    {
        $page = $request->input('page',1);
        $limit = $request->input('limit',10);
        $query = static::filter($this->getModel(), $request);
        $count = $query->count();
        $data = $query->skip(($page-1)*$limit)->take($limit)->toSql();
        return $this->json(StatusCode::SUCCESS, ['data' => $data,'count'=>$count]);
    }

    /**
     * 获取
     * @param int $id 资源Id
     * @return mixed
     */
    public function show($id)
    {
        $data = $this->getModel()->findOrFail($id);
        return $this->json(StatusCode::SUCCESS, ['data' => $data]);
    }

    /**
     * 创建资源
     * @return mixed
     * @throws \App\Exceptions\FromValidator
     */
    public function store()
    {
        $data = $this->validate($this->addRule());
        $status = $this->getModel()->create($data);
        if ($status) {
            return $this->json(StatusCode::SUCCESS);
        }
        return $this->json(StatusCode::ERROR);
    }

    /**
     * 修改
     * @param int $id 资源Id
     * @return mixed
     * @throws \App\Exceptions\FromValidator
     */
    public function update($id)
    {
        $data = $this->validate($this->editRule());
        $status = $this->getModel()->findOrFail($id)->update($data);
        if ($status) {
            return $this->json(StatusCode::SUCCESS);
        }
        return $this->json(StatusCode::ERROR);
    }

    /**
     * 删除
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $status = $this->getModel()->destroy($id);
        if ($status) {
            return $this->json(StatusCode::SUCCESS);
        }
        return $this->json(StatusCode::ERROR);
    }

    protected abstract function getModel(): Builder;

    protected abstract function addRule(): array;

    protected abstract function editRule(): array;
}
