<?php

namespace Sczts\Skeleton\Http;

class StatusCode
{
    const SUCCESS = ['code' => 0, 'msg' => '请求成功'];
    const ERROR = ['code' => -1, 'msg' => '请求失败'];

    const NO_PERMISSION = ['code' => -1, 'msg' => '无操作权限'];


    // Open Api
    const LOGIN_FAILED = ['code' => -1, 'msg' => '登录认证失败'];
    const AUTHENTICATION_FAILED = ['code' => -1, 'msg' => 'token 认证失败'];
    const AUTHENTICATION_SUCCESS = ['code' => 0, 'msg' => 'token 认证成功'];
    const AUTHENTICATION_s = ['code' => 0, 'msg' => 'token 已过期，请重新生成'];
    const INSUFFICIENT_BALANCE = ['code' => -1, 'msg' => '余额不足，请先充值'];
    const TOKEN_EXPIRATION = ['code' => -1,'msg'=> 'token 已过期，请重新申请'];
    const DATA_NOT_FOUND = ['code' => -1,'msg'=> '数据未找到'];
}
