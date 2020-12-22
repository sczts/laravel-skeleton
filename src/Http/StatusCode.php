<?php

namespace Sczts\Skeleton\Http;

class StatusCode
{
    const SUCCESS = ['code' => 0, 'msg' => '请求成功'];
    const ERROR = ['code' => -1, 'msg' => '请求失败'];

    const NOT_LOGGED_IN = ['code' => 401, 'msg' => '用户未登录'];
    const NO_PERMISSION = ['code' => 401, 'msg' => '无操作权限'];

}
