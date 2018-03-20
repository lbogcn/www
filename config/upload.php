<?php

return array(
    'driver' => env('UPLOAD_DRIVER', 'local'),

    'services' => array(
        'local' => array(
            'driver' => 'local',
            'root' => public_path('static'),
            'url' => 'http://' . env('DOMAIN_WEB') . '/static',
            'action' => '/upload',
        ),

        'qiniu' => array(
            'driver' => 'qiniu',

            'access_key' => env('QINIU_ACCESS_KEY'),
            'secret_key' => env('QINIU_SECRET_KEY'),

            // 私有空间，用于buff
            'private_bucket' => env('QINIU_PRIVATE_BUCKET'),

            // 公有空间
            'public_bucket' => env('QINIU_PUBLIC_BUCKET'),
            'public_domain' => env('QINIU_PUBLIC_DOMAIN'),

            // 回调地址，设置不同回调地址可根据不同场影返回不同格式的上传结果数据
            'callback' => env('QINIU_CALLBACK'),

            'action' => '//upload-z2.qiniup.com',
        ),
    ),
);