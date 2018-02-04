<?php

return array(
    'home' => array(
        'name' => 'home',
        'title' => '首页',
        'childs' => array(
            'dashboard' => array(
                'name' => 'dashboard',
                'title' => '仪表盘',
                'icon' => 'el-icon-lb-dashboard',
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
                'icon' => 'el-icon-lb-fabuwenzhang',
                'childs' => array(
                    'article' => array(
                        'name' => 'article',
                        'title' => '文章管理',
                        'icon' => 'el-icon-lb-24',
                    ),
                    'questionnaires' => array(
                        'name' => 'questionnaires',
                        'title' => '问卷管理',
                        'icon' => 'el-icon-lb-wenjuantiaocha',
                    ),
                    'message' => array(
                        'name' => 'message',
                        'title' => '留言管理',
                        'icon' => 'el-icon-lb-liuyan',
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
                'icon' => 'el-icon-lb-permissions-list',
                'childs' => array(
                    'user' => array(
                        'name' => 'user',
                        'title' => '用户管理',
                        'icon' => 'el-icon-lb-yonghu',
                    ),
                    'role' => array(
                        'name' => 'role',
                        'title' => '角色管理',
                        'icon' => 'el-icon-lb-jiaoseguanli',
                    ),
                    'node' => array(
                        'name' => 'node',
                        'title' => '节点管理',
                        'icon' => 'el-icon-lb-quanxianguanli',
                    ),
                    'menu' => array(
                        'name' => 'menu',
                        'title' => '菜单管理',
                        'icon' => 'el-icon-lb-caidan',
                    )
                )
            ),
            'log' => array(
                'name' => 'log',
                'title' => '日志管理',
                'icon' => 'el-icon-lb-log',
                'childs' => array(
                    'error' => array(
                        'name' => 'error',
                        'title' => '错误日志',
                        'icon' => 'el-icon-lb-error',
                    ),
                    'operation' => array(
                        'name' => 'operation',
                        'title' => '操作日志',
                        'icon' => 'el-icon-lb-caozuo',
                    ),
                ),
            ),
        ),
    )
);