<?php

namespace Sczts\Skeleton\Http;

class StatusCode
{
    const SUCCESS = ['code' => 0, 'msg' => '请求成功'];
    const ERROR = ['code' => -1, 'msg' => '请求失败'];

    const NO_PERMISSION = ['code' => -1, 'msg' => '无操作权限'];
}
