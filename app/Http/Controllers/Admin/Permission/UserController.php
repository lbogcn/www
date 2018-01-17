<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Components\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(Request $request, Admin $user)
    {
        $keys = ['username'];
        /** @var \Illuminate\Pagination\LengthAwarePaginator $paginate */
        $paginate = $this->buildSearch($user, $request, $keys)
            ->orderBy('id', 'desc')
            ->paginate();

        return ApiResponse::success($paginate->toArray());
    }

}