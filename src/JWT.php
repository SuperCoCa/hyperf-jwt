<?php


namespace SuperCoCa\JWT;


use Hyperf\Redis\Redis;
use SuperCoCa\JWT\Exception\JWTException;
use SuperCoCa\JWT\Exception\TokenValidException;

class JWT extends AbstractJWT implements JWTInterface
{
    protected $config;

    protected $blackList;

    protected $redis;

    public function __construct(Redis $redis, BlackList $blackList) {
        $this->config = config('jwt');
        $this->blackList = $blackList;
        $this->redis = $redis;
    }

    /**
     * 生成token
     * @param array $data token中的自定义数据
     * @param string $type token类型
     * @return string
     */
    public function generate($data, $type = self::ACCESS_TOKEN) {
        if (!isset($this->config['alg'], $this->supportedAlgs)) throw new JWTException('当前加密算法不支持');

        // 生成头部
        $header = ['typ' => 'JWT', 'alg' => $this->config['alg']];
        $header = $this->safeBase64Encode(json_encode($header), JSON_UNESCAPED_UNICODE);

        // 生成载体
        $time = time();
        $builder = new Builder();
        $builder->setJti(uniqid());
        $builder->setIat($time);
        $builder->setNbf($time);
        $builder->setExp($this->calcExp($time, $type));
        $builder->setType($type);
        $builder->setData($data);
        if (isset($data[$this->config['aud_key']])) $builder->setAud($data[$this->config['aud_key']]);

        $payload = $builder->toArray();
        $payload = $this->safeBase64Encode(json_encode($payload, JSON_UNESCAPED_UNICODE));

        // 生成签名
        $key = $this->getKey($this->config['alg']);
        $signature = $this->sign($header . '.' . $payload, $key, $this->config['alg']);

        $builder->setToken($header . '.' . $payload . '.' . $signature);

        return $builder;
    }

    /**
     * 验证token
     * @param $token
     * @return Builder
     */
    public function verify($token) {
        $segments = explode('.', $token);
        
        if (count($segments) !== 3) throw new TokenValidException('token不合法', 401);
        [$header, $payload, $signature] = $segments;

        $headerArr = json_decode($this->safeBase64Decode($header), true);
        if (empty($headerArr['alg'])) throw new TokenValidException('token不合法', 401);

        $signResult = $this->sign($header . '.' . $payload, $this->getKey($headerArr['alg']), $headerArr['alg']);
        if ($signResult != $signature) throw new TokenValidException('token验证失败', 401);

        $time = time();
        $payload = json_decode($this->safeBase64Decode($payload), true);
        if (isset($payload['exp']) && $payload['exp'] < $time) throw new TokenValidException('token已失效', 401);
        if (isset($payload['iat']) && $payload['iat'] > $time) throw new TokenValidException('token已失效', 401);
        if (isset($payload['nbf']) && $payload['nbf'] > $time) throw new TokenValidException('token未生效，请稍后重试', 401);
        if (isset($payload['aud'])) {
            $isBlock = $this->blackList->isBlock($payload['aud'], $payload['jti'], $payload['type']);
            if ($isBlock) throw new TokenValidException('token已失效', 401);
        }

        return new Builder($payload);
    }

    /**
     * 刷新token
     * @param $token
     * @return string|Builder
     */
    public function refresh($token) {
        $accessBuilder = $this->verify($token);
        $accessData = $accessBuilder->getData();

        $key = $this->config['cache_prefix'] . ':' . self::REFRESH_TOKEN . ':' . $accessBuilder->getAud();
        $refreshToken = $this->redis->get($key);
        if (!$refreshToken) throw new TokenValidException('请重新登录', 401);

        $refreshBuilder = $this->verify($refreshToken);
        $refreshData = $refreshBuilder->getData();
        $exp = $refreshBuilder->getExp();
        if ($exp > 0) $this->blackList->add($accessBuilder->getAud(), $accessBuilder->getJti(), $exp);

        return $this->generate($accessData);
    }

    /**
     * 生成刷新token
     * @param $data
     * @return string|Builder
     */
    public function generateRefreshToken($data) {
        $builder = $this->generate($data, self::REFRESH_TOKEN);

        $key = $this->config['cache_prefix'] . ':' . self::REFRESH_TOKEN . ':' . $builder->getAud();
        $this->redis->set($key, $builder->getToken());
        $this->redis->expireAt($key, $builder->getExp());

        return $builder;
    }

    /**
     * 生成签名
     * @param $input
     * @param $key
     * @param string $alg
     * @return mixed
     */
    private function sign($input, $key, $alg = 'HS256') {
        $signature = '';

        [$function, $algorithm] = $this->supportedAlgs[$alg];
        switch ($function) {
            case 'hash_hmac':
                $signature = hash_hmac($algorithm, $input, $key, true);
                break;
            case 'openssl':
                $signature = openssl_sign($input, $signature, $key, $algorithm);
                break;
        }

        return $this->safeBase64Encode($signature);
    }

    /**
     * 获取加密算法的秘钥
     * @param $alg
     * @param string $type
     * @return mixed|string
     */
    private function getKey($alg, $type = 'public') {
        $key = '';
        switch (true) {
            case in_array($alg, $this->symmetricAlgs):
                $key = $this->config['key']['secret'];
                break;
            case in_array($alg, $this->asymmetricAlgs):
                $key = $this->config['key'][$type];
                break;
        }

        return $key;
    }

    /**
     * 进行url安全的base64编码
     * @param string $string
     * @return mixed
     */
    private function safeBase64Encode($string) {
        return str_replace('=', '', strtr(base64_encode($string), '+/', '-_'));
    }

    /**
     * 进行url安全的base64解码
     * @param string $string
     * @return bool|string
     */
    private function safeBase64Decode($string) {
        $remainder = strlen($string) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $string .= str_repeat('=', $padlen);
        }

        return base64_decode(strtr($string, '-_', '+/'));
    }

    /**
     * 计算token失效时间
     * @param $time
     * @param $type
     * @return mixed
     */
    private function calcExp($time, $type) {
        switch ($type) {
            case self::ACCESS_TOKEN:
                return $time + $this->config['access_exp'];
            case self::REFRESH_TOKEN:
                return $time + $this->config['refresh_exp'];
        }
    }
}