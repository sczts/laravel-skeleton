<?php


namespace Sczts\Skeleton\Traits;


use Illuminate\Support\Facades\Validator;
use Sczts\Skeleton\Exceptions\FromValidator;

trait ControllerTrait
{
    /**
     * 响应 json 数据
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

    /**
     * 表单验证
     * @param $rule
     * @param array $attribute
     * @return array
     * @throws FromValidator
     */
    public function validator($rule, $attribute = [])
    {
        $validator = Validator::make(request()->all(), $rule, [], $attribute);
        if ($validator->fails()) {
            throw new FromValidator($validator->errors()->first());
        }
        return $validator->validate();
    }
}
