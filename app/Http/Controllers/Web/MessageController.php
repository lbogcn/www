<?php

namespace App\Http\Controllers\Web;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    /**
     * 获取留言
     * @param Request $request
     * @return ApiResponse|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        $messages = Message::where('display', Message::DISPLAY_SHOW)
            ->orderBy('id', 'desc')
            ->simplePaginate(20);
        $data = array(
            'messages' => $messages,
            'type' => 'message',
            'title' => '留言板',
        );

        if ($request->ajax()) {
            $ajaxData = array(
                'view' => view('web.message.list', $data)->render(),
                'title' => $data['title'] . ' - ' . config('app.name'),
            );

            return ApiResponse::success($ajaxData);
        } else {
            return view('web.layout', $data);
        }
    }


}