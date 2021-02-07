<?php


namespace SuperCoCa\JWT;


interface JWTInterface
{
    public function generate($data, $type);

    public function verify($token);
    
    public function refresh($token);
}