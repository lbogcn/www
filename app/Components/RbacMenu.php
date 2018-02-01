<?php

namespace App\Components;

use App\Models\AdminVisibleMenu;
use Illuminate\Database\Eloquent\Relations\Relation;

class RbacMenu
{

    /**
     * 获取完整菜单
     * @return array
     */
    public function getAll()
    {
        return $this->format(config('menu'));
    }

    /**
     * 格式化
     * @param $menus
     * @param string $parent
     * @return array
     */
    private function format($menus, $parent = ''): array
    {
        $rows = array();

        foreach ($menus as $menu) {
            $route = "{$parent}/{$menu['name']}";
            $menu['route'] = $route;

            if (isset($this->visibleMenuGroups()[$route])) {
                $menu['roles'] = $this->visibleMenuGroups()[$route];
            }

            if (isset($menu['childs'])) {
                $menu['childs'] = $this->format($menu['childs'], $route);
            }

            $rows[] = $menu;
        }

        return $rows;
    }

    /**
     * 获取菜单
     * @return array
     */
    private function visibleMenuGroups()
    {
        static $groups = [];
        static $loaded = false;

        if (!$loaded) {
            $loaded = true;
            $menus = AdminVisibleMenu::with(['role' => function(Relation $query) {$query->select(['id', 'role']);}])->get();
            /** @var AdminVisibleMenu $menu */
            foreach ($menus as $menu) {
                if (empty($menu->role)) {continue;}

                if (!isset($groups[$menu->route])) {
                    $groups[$menu->route] = [];
                }

                $groups[$menu->route][] = $menu->role->toArray();
            }
        }

        return $groups;
    }

}