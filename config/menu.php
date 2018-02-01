<?php

return array(
    'home' => array(
        'name' => 'home',
        'title' => '首页',
        'childs' => array(
            'dashboard' => array(
                'name' => 'dashboard',
                'title' => '仪表盘'
            )
        ),
    ),

    'control' => array(
        'name' => 'control',
        'title' => '控制中心',
        'childs' => array(
            'permission' => array(
                'name' => 'permission',
                'title' => '权限管理',
                'childs' => array(
                    'user' => array(
                        'name' => 'user',
                        'title' => '用户管理'
                    ),
                    'role' => array(
                        'name' => 'role',
                        'title' => '角色管理'
                    ),
                    'node' => array(
                        'name' => 'node',
                        'title' => '节点管理'
                    ),
                    'menu' => array(
                        'name' => 'menu',
                        'title' => '菜单管理'
                    )
                )
            )
        ),
    )
);