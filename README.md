## 使用方法
#### 1. 安装
```shell script
composer require supercoca/hyperf-jwt
```

#### 2. 生成配置文件
```shell script
php bin/hyperf.php jwt:publish --config
```

## 配置说明
```php
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
        // 非对称加密算法秘钥
        'secret' => env('SECRET_KEY', 'hyperfjwt'),
        // 对称加密算法秘钥
        'public' => env('PUBLIC_KEY'),
        'private' => env('PRIVATE_KEY'),
    ],

    // 使用的加密算法
    'alg' => env('ALG', 'HS256'),
];
```