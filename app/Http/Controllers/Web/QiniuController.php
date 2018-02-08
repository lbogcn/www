<?php

namespace App\Http\Controllers\Web;

use App\Components\ApiResponse;
use App\Components\Qiniu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QiniuController extends Controller
{

    /**
     * 回调
     * @param Request $request
     * @param Qiniu $qiniu
     * @return ApiResponse
     */
    public function index(Request $request, Qiniu $qiniu)
    {
        $body = $request->getContent();
        $hash = json_decode($body, true);
        $contentType = $request->server('HTTP_CONTENT_TYPE');
        $authorization = $request->server('HTTP_AUTHORIZATION');
        $url = config('global.qiniu.callback');

        if (is_array($hash) && $qiniu->verify($contentType, $authorization, $url, $body)) {
            $data = array(
                'url' => $qiniu->moveToPublic($hash),
                'body' => array(
                    'size' => $hash['size'],
                    'height' => $hash['height'],
                    'width' => $hash['width'],
                    'ext' => $hash['ext'],
                )
            );

            return ApiResponse::success($data);
        }

        return abort(404);
    }

}