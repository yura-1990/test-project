<?php

namespace App\JWT;

interface TokenGenerator
{
    public function generate($user);
}

class JWTToken implements TokenGenerator
{
   public function generate($user, $secretKey='cometollamo')
    {
        $payload = [
            'user_id' => $user['id'],
            'user_email' => $user['email'],
            'user_role' => $user['role_id'],
            'exp' => time() + 3600, // Expiration time (e.g., 1 hour from now)
        ];

        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($payload);

        $header = $this->base64UrlEncode($header);
        $payload = $this->base64UrlEncode($payload);

        $signature = hash_hmac('sha256', "$header.$payload", $secretKey, true);
        $signature = $this->base64UrlEncode($signature);

        return "$header.$payload.$signature";
    }

    public function base64UrlEncode($data): string
    {
        $urlSafeData = strtr(base64_encode($data), '+/', '-_');
        return rtrim($urlSafeData, '=');
    }
}
