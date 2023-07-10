<?php

namespace App\Auth;

class Auth
{
    public static function user()
    {
        $token = $_SERVER['HTTP_AUTHORIZATION'];

        $token = str_replace('Bearer ', '', $token);

        $tokenParts = explode('.', $token);

        $payload = base64_decode($tokenParts[1]);

        return json_decode($payload);
    }
}