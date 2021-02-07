<?php


namespace SuperCoCa\JWT;


class ConfigProvider
{
    public function __invoke() {
        return [
            'dependencies' => [],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'commands' => [],
            'listeners' => [],
            'publish' => [
                'id' => 'config',
                'description' => 'jwt',
                'source' => __DIR__ . '/../publish/jwt.php',
                'destination' => BASE_PATH . '/config/autoload/jwt.php'
            ],
        ];
    }
}