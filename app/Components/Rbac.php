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
     * 获取菜单
     * @return array
     */
    public function getMenu(): array
    {
        if (!Session::has('menus') || config('app.debug')) {
            $this->admin->load('roles');
            $allMenus = (new RbacMenu())->getAll();
            $userRoleIds = array_column($this->admin->roles->toArray(), 'id');
            $menus = $this->formatUserMenus($allMenus, $userRoleIds);

            Session::put('menus', $menus);
        }

        return Session::get('menus');
    }

    /**
     * 格式化菜单
     * @param array $allMenus
     * @param array $userRoleIds
     * @return array
     */
    private function formatUserMenus(array $allMenus, array $userRoleIds)
    {
        $menus = array_column($allMenus, null, 'name');
        foreach ($menus as $name => $menu) {
            if (!$this->checkRoleMenu($menu, $userRoleIds)) {
                unset($menus[$name]);
                continue;
            }

            if (isset($menu['roles'])) {
                unset($menu['roles']);
            }

            if (isset($menu['childs'])) {
                $menu['childs'] = $this->formatUserMenus($menu['childs'], $userRoleIds);
            }

            $menus[$name] = $menu;
        }

        return $menus;
    }

    /**
     * 验证用户是否有权限访问菜单
     * @param $menu
     * @param $userRoleIds
     * @return bool
     */
    private function checkRoleMenu($menu, $userRoleIds)
    {
        if (isset($menu['roles']) && is_array($menu['roles'])) {
            $menuRoleIds = array_column($menu['roles'], 'id');
            foreach ($userRoleIds as $userRoleId) {
                if (in_array($userRoleId, $menuRoleIds)) {
                    return true;
                }
            }

            return false;
        }

        return true;
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