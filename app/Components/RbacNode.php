<?php

namespace App\Components;

use App\Models\AdminNode;
use App\Providers\RouteServiceProvider;
use ReflectionClass;
use Route;

class RbacNode
{

    private $namespace;

    /**
     * RbacNode constructor.
     */
    public function __construct()
    {
        /** @var RouteServiceProvider $provider */
        $provider = app()->getProvider(RouteServiceProvider::class);
        $namespace = $provider->getNamespace() . '\\';
        $this->namespace = $namespace;
    }

    /**
     * 执行更新
     * @throws \Exception
     * @throws \Throwable
     * @return array
     */
    public function update()
    {
        $nodes = array_column($this->findNodes(), null, 'route');
        $adminNodes = AdminNode::all();

        $counts = array(
            'c' => 0,
            'u' => 0,
            'd' => 0,
        );

        foreach ($adminNodes as $adminNode) {
            // 删除无效的节点
            if (!isset($nodes[$adminNode->route])) {
                $adminNode->delete();
                $counts['d']++;
                continue;
            }

            // 更新已存在的节点
            $node = array_pull($nodes, $adminNode->route);
            $adminNode->group = $node['group'];
            $adminNode->node = $node['node'];
            $adminNode->route = $node['route'];
            $adminNode->saveOrFail();
            $counts['u']++;
        }

        // 写入新节点
        $nodes = array_values($nodes);
        $counts['c'] = count($nodes);
        if ($counts['c'] > 0) {
            AdminNode::insert($nodes);
        }

        return $counts;
    }

    /**
     * 获取有效节点列表
     * @return array
     */
    private function findNodes(): array
    {
        $nodes = [];

        /** @var \Illuminate\Routing\Route $route */
        foreach (Route::getRoutes()->getRoutes() as $route) {
            $uses = $route->getAction()['uses'];
            list($permission, $act) = $this->explodeUses($uses);
            if (empty($permission)) {
                continue;
            }

            foreach ($permission['nodes'] as $node) {
                if ($node['action'] != $act) {
                    continue;
                }

                $nodes[] = array(
                    'group' => $permission['title'],
                    'node' => $node['name'],
                    'route' => $this->sliceNamespace($uses),
                );
            }
        }

        return $nodes;
    }

    /**
     * 截取命名空间
     * @param $uses
     * @return string
     */
    private function sliceNamespace($uses)
    {
        if (substr($uses, 0, strlen($this->namespace)) == $this->namespace) {
            return substr($uses, strlen($this->namespace));
        }

        return $uses;
    }

    /**
     * 截取controller类和action
     * @param string $uses
     * @return array
     */
    private function explodeUses($uses): array
    {
        list($ctl, $act) = explode('@', $uses);
        if (!class_exists($ctl)) {
            return [null, null];
        }

        $cls = new ReflectionClass($ctl);
        $permission = $cls->getConstant('PERMISSION');

        if (!$permission) {
            return [null, null];
        }

        return [$permission, $act];
    }

}