<?php

namespace App\Http\Controllers\Web;

use App\Components\ApiResponse;
use App\Components\Upload\Upload;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QiniuController extends Controller
{

    /**
     * 回调
     * @param Request $request
     * @param Upload $upload
     * @return ApiResponse
     */
    public function index(Request $request, Upload $upload)
    {
        return ApiResponse::success($upload->storage($request));
    }

}