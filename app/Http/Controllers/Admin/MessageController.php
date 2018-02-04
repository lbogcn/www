<?php

namespace App\Http\Controllers\Admin;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    const PERMISSION = array(
        'title' => '留言管理',
        'nodes' => [
            ['action' => 'index', 'name' => '列表'],
            ['action' => 'update', 'name' => '更新'],
            ['action' => 'destroy', 'name' => '删除'],
        ],
    );

    /**
     * 列表
     * @param Request $request
     * @param Message $message
     * @return ApiResponse
     */
    public function index(Request $request, Message $message)
    {
        $keys = ['email'];
        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginate */
        $paginate = $this->buildSearch($message, $request, $keys)
            ->orderBy('id', 'desc')
            ->paginate();
        $data = array(
            'paginate' => $paginate->toArray()
        );

        return ApiResponse::success($data);
    }

    /**
     * 更新
     * @param Request $request
     * @param Message $message
     * @return ApiResponse
     */
    public function update(Request $request, Message $message)
    {
        $this->validate($request, array(
            'nickname' => ['required', 'min:1', 'max:12'],
            'display' => ['required', 'integer'],
            'content' => ['required', 'min:1', 'max:500'],
        ));

        $data = $request->only(['nickname', 'display', 'content']);
        $message->update($data);

        return ApiResponse::success(null);
    }

    /**
     * 删除
     * @param Message $message
     * @return ApiResponse
     * @throws \Exception
     */
    public function destroy(Message $message)
    {
        $message->delete();

        return ApiResponse::success(null);
    }

}