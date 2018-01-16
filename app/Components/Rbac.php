<?php

namespace App\Components;

use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Session;

class Rbac
{

    /** @var Admin */
    private $admin;

    /**
     * Rbac constructor.
     * @param Admin $admin
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    /**
     * 验证权限
     * @param \Illuminate\Routing\Route $route
     * @return bool
     */
    public function check($route): bool
    {
        $permissions = $this->loadPermissions();
        /** @var RouteServiceProvider $provider */
        $provider = app()->getProvider(RouteServiceProvider::class);
        $namespace = $provider->getNamespace() . '\\';

        // 命名空间不一致
        if ($namespace != substr($route->getActionName(), 0, strlen($namespace))) {
            return false;
        }

        // 截掉命名空间部分
        $action = substr($route->getActionName(), strlen($namespace));

        return in_array($action, $permissions);
    }

    /**
     * 获取用户权限
     * @return \App\Models\AdminNode[]
     */
    private function loadPermissions()
    {
        if (!Session::has('permissions') || config('app.debug')) {
            $this->admin->load(['roles', 'roles.permissions']);
            $permissions = array();

            foreach ($this->admin->roles as $role) {
                /** @var \App\Models\AdminNode $permission */
                foreach ($role->permissions as $permission) {
                    $permissions[$permission->id] = $permission->route;
                }
            }

            Session::put('permissions', array_values($permissions));
        }

        return Session::get('permissions');
    }
}