<?php

namespace App\Components;

use Illuminate\Http\JsonResponse;

class ApiResponse extends JsonResponse
{

    /**
     * ApiResponse constructor.
     * @param mixed|null $data
     * @param int $code
     * @param string $msg
     */
    public function __construct($code, $data, $msg)
    {
        $resp = array(
            'code' => $code,
            'data' => $data,
            'msg' => $msg,
        );

        parent::__construct($resp, 200, [], 0);
    }

    /**
     * 输出成功
     * @param $data
     * @param int $code
     * @param string $msg
     * @return ApiResponse
     */
    public static function success($data, $code = 0, $msg = 'success')
    {
        return new self($code, $data, $msg);
    }

    /**
     * 输出失败
     * @param string|array $msg
     * @param $code
     * @param null $data
     * @return ApiResponse
     */
    public static function fail($msg, $code = null, $data = null)
    {
        if (is_array($msg) && is_null($code)) {
            $code = $msg[0];
            $msg = $msg[1];
        }

        if ($code == 0) {
            $code = -1;
        }

        return new self($code, $data, $msg);
    }

}