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

    'operation' => array(
        'name' => 'operation',
        'title' => '运营中心',
        'childs' => array(
            'content' => array(
                'name' => 'content',
                'title' => '内容管理',
                'childs' => array(
                    'article' => array(
                        'name' => 'article',
                        'title' => '文章管理'
                    ),
                ),
            ),
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
            ),
            'log' => array(
                'name' => 'log',
                'title' => '日志管理',
                'childs' => array(
                    'error' => array(
                        'name' => 'error',
                        'title' => '错误日志',
                    ),
                    'operation' => array(
                        'name' => 'operation',
                        'title' => '操作日志',
                    ),
                ),
            ),
        ),
    )
);