<?php


namespace SuperCoCa\JWT;


class Builder
{
    /**
     * @var array
     */
    private $builder;

    /**
     * @var string
     */
    private $token;

    public function __construct($builder = []) {
        $this->builder = $builder;
    }

    /**
     * 获取builder
     * @return array
     */
    public function toArray() {
        return $this->builder;
    }

    /**
     * 获取发行人
     * @return mixed
     */
    public function getIss() {
        return $this->builder['iss'] ?? null;
    }

    /**
     * 设置发行人
     * @param string $value
     */
    public function setIss($value) {
        $this->builder['iss'] = $value;
    }

    /**
     * 获取过期时间
     * @return mixed
     */
    public function getExp() {
        return $this->builder['exp'] ?? null;
    }

    /**
     * 设置过期时间
     * @param $value
     */
    public function setExp($value) {
        $this->builder['exp'] = $value;
    }

    /**
     * 获取发布者
     * @return mixed
     */
    public function getAud() {
        return $this->builder['aud'] ?? null;
    }

    /**
     * 设置发布者
     * @param $value
     */
    public function setAud($value) {
        $this->builder['aud'] = $value;
    }

    /**
     * 获取生效时间
     * @return mixed
     */
    public function getNbf() {
        return $this->builder['nbf'] ?? null;
    }

    /**
     * 设置生效时间
     * @param integer $value
     */
    public function setNbf($value) {
        $this->builder['nbf'] = $value;
    }

    /**
     * 获取发布时间
     * @return mixed
     */
    public function getIat() {
        return $this->builder['nbf'] ?? null;
    }

    /**
     * 设置发布时间
     * @param integer $value
     */
    public function setIat($value) {
        $this->builder['iat'] = $value;
    }

    /**
     * 获取JWT ID
     * @return mixed
     */
    public function getJti() {
        return $this->builder['jti'] ?? null;
    }

    /**
     * 设置JWT ID
     * @param string $value
     */
    public function setJti($value) {
        $this->builder['jti'] = $value;
    }

    /**
     * 获取token类型
     * @return mixed
     */
    public function getType() {
        return $this->builder['type'] ?? null;
    }

    /**
     * 设置token类型
     * @param $value
     */
    public function setType($value) {
        $this->builder['type'] = $value;
    }

    /**
     * 获取自定义数据
     * @return mixed
     */
    public function getData() {
        return $this->builder['data'] ?? null;
    }

    /**
     * 设置自定义数据
     * @param array $value
     */
    public function setData($value) {
        $this->builder['data'] = $value;
    }

    /**
     * 获取token
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * 设置token
     * @param string $value
     */
    public function setToken($value) {
        $this->token = $value;
    }
}