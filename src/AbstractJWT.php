<?php


namespace SuperCoCa\JWT;


class AbstractJWT
{
    public const ACCESS_TOKEN = 'access';
    public const REFRESH_TOKEN = 'refresh';

    /**
     * @var array 支持的加密方法
     */
    protected $supportedAlgs = [
        'HS256' => ['hash_hmac', 'SHA256'],
        'HS384' => ['hash_hmac', 'SHA384'],
        'HS512' => ['hash_hmac', 'SHA512'],
        'RS256' => ['openssl', 'SHA256'],
        'RS384' => ['openssl', 'SHA384'],
        'RS512' => ['openssl', 'SHA512'],
        'ES256' => ['openssl', 'SHA256'],
    ];

    /**
     * @var array 对称加密算法
     */
    protected $symmetricAlgs = [
        'HS256',
        'HS384',
        'HS512',
    ];

    /**
     * @var array 非对称加密算法
     */
    protected $asymmetricAlgs = [
        'RS256',
        'RS384',
        'RS512',
        'ES256',
    ];
}