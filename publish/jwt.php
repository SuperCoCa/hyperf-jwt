<?php

return [
    // 访问token过期时间（单位：秒）
    'access_exp' => env('ACCESS_EXP', 7200),

    // 刷新token过期时间（单位：秒）
    'refresh_exp' => env('REFRESH_EXP', 28800),

    // jwt储存用户的键名
    'aud_key' => env('AUD_KEY', 'uid'),

    // 缓存的键名前缀
    'cache_prefix' => env('CACHE_PREFIX', 'jwt'),

    // 加密算法的键值
    'key' => [
        'secret' => env('SECRET_KEY', 'hyperfjwt'),
        'public' => env('PUBLIC_KEY'),
        'private' => env('PRIVATE_KEY'),
    ],

    // 使用的加密算法
    'alg' => env('ALG', 'HS256'),
];