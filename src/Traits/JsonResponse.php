<?php


namespace Sczts\Skeleton\Traits;


use Sczts\Skeleton\Http\StatusCode;

trait JsonResponse
{
    /**
     * 返回请求成功数据
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data = null)
    {
        $data = $data == null ? [] : ['data' => $data];
        return $this->json(StatusCode::SUCCESS, $data);
    }

    /**
     * 返回请求成功信息
     * @param null $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function message($message = null)
    {
        $data = $message == null ? [] : ['msg' => $message];
        return $this->json(StatusCode::SUCCESS, $data);
    }

    /**
     * 返回请求失败信息
     * @param null $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function failed($message = null)
    {
        $data = $message == null ? [] : ['msg' => $message];
        return $this->json(StatusCode::ERROR, $data);
    }

    /**
     * 请求成功，返回分页类型数据
     * @param $data
     * @param $count
     * @return \Illuminate\Http\JsonResponse
     */
    protected function withCount($data, $count)
    {
        return $this->json(StatusCode::SUCCESS, [
            'data' => $data,
            'count' => $count
        ]);
    }

    /**
     * 返回 json 数据
     * @param $code
     * @param array $attrs
     * @param int $status
     * @param array $headers
     * @param int $options
     * @return \Illuminate\Http\JsonResponse
     */
    protected function json($code, $attrs = [], $status = 200, array $headers = [], $options = 0)
    {
        $response = array_merge($code, $attrs);
        return response()->json($response, $status, $headers, $options);
    }
}