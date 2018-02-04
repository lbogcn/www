<?php

namespace App\Components;


class Errors
{

    /**
     * 未登录
     * @param string|null $msg
     * @return array
     */
    public static function notLogin($msg = null): array
    {
        $msg = is_null($msg) ? trans('errors.' . snake_case(__FUNCTION__)) : $msg;

        return [-10001, $msg];
    }

    /**
     * 权限不足
     * @param string|null $msg
     * @return array
     */
    public static function permissionDenied($msg = null): array
    {
        $msg = is_null($msg) ? trans('errors.' . snake_case(__FUNCTION__)) : $msg;

        return [-10002, $msg];
    }

    /**
     * 记录不存在
     * @param string|null $msg
     * @return array
     */
    public static function modelNotFound($msg = null)
    {
        $msg = is_null($msg) ? trans('errors.' . snake_case(__FUNCTION__)) : $msg;

        return [-10003, $msg];
    }

    /**
     * 密码错误
     * @param string|null $msg
     * @return array
     */
    public static function passwordError($msg = null)
    {
        $msg = is_null($msg) ? trans('errors.' . snake_case(__FUNCTION__)) : $msg;

        return [-10004, $msg];
    }

}