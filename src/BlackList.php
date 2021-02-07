<?php


namespace SuperCoCa\JWT;


use Hyperf\Redis\Redis;

class BlackList
{
    protected $config;
    
    protected $redis;

    public function __construct(Redis $redis) {
        $this->config = config('jwt');
        $this->redis = $redis;
    }

    public function add($aud, $jti, $exp) {
        $key = $this->config['cache_prefix'] . ':' . JWT::ACCESS_TOKEN . ':' . $aud;
        $this->redis->sAdd($key, $jti);
        $this->redis->expireAt($key, $exp);
    }

    public function isBlock($aud, $jti, $type) {
        $key = $this->config['cache_prefix'] . ':' . JWT::ACCESS_TOKEN . ':' . $aud;

        return $this->redis->sIsMember($key, $jti);;
    }
}